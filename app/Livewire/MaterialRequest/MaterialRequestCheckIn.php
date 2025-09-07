<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use Livewire\Component;
use App\Models\Recording;
use App\Helper\WithFilter;
use App\Models\Department;
use Livewire\Attributes\Title;
use App\Models\MaterialRequest;
use App\Exports\RecordingExport;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use App\Enum\MaterialRequestStatus;
use Illuminate\Support\Facades\Auth;

#[Title('Check material request')]
final class MaterialRequestCheckIn extends Component
{
    use WithFilter;

    public string $date = '';

    #[Validate('required|string|in:Approved,Rejected')]
    public string $decision = '';

    #[Validate('required|string|in:Exit,Entry')]
    public string $action = '';

    #[Validate('required|exists:material_requests,id')]
    public $material_request_id = '';

    public function ResetFilter(): void
    {
        $this->reset('department', 'date', 'search');
    }

    public function export()
    {
        return (new RecordingExport($this->date))->download('recordings.xlsx');
    }

    #[Computed]
    public function rows()
    {
        return Recording::with('user', 'requestable:id,company,reference')->whereHasMorph(
            'requestable',
            [MaterialRequest::class]
        )->when($this->date, function ($query) {
            $query->whereDate('created_at', $this->date);
        })->latest('id')->paginate();
    }

    public function recordSecurityCheck()
    {

        $this->validate();
        $item = MaterialRequest::findOrFail($this->car_request_id);
        // Vérifier expiration
        if ($item->isExpired()) {
            flash()->success('request expired');

            return;
        }

        $item->recordings()->create([
            'action' => $this->action,      // 'entry' ou 'exit'
            'decision' => $this->decision,    // 'validated' ou 'rejected'
            'checked_at' => now(),
            'user_id' => Auth::user()->id,
        ]);
        $this->reset();
        $this->dispatch('close-modal', name: 'security-check');
        flash()->success('Record security check added');
    }

    public function render()
    {
        $departments = Department::select('id', 'name')->get();
        $materialRequests = MaterialRequest::select('id', 'status', 'reference', 'created_at', 'expire_at')
            ->where('status', MaterialRequestStatus::Approved)->get();
        return view('livewire.material-request.material-request-check-in', compact('departments', 'materialRequests'));
    }
}
