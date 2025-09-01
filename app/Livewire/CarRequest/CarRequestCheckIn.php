<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Helper\WithFilter;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\Recording;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Livewire\Component;

use function compact;

final class CarRequestCheckIn extends Component
{
    use WithFilter;

    public string $date = '';

    #[Validate('required|string|in:Approved,Rejected')]
    public string $decision = '';

    #[Validate('required|string|in:Exit,Entry')]
    public string $action = '';

    #[Validate('required|string|exists:car_requests,id')]
    public $car_request_id = '';

    public function ResetFilter(): void
    {
        $this->reset('department', 'date', 'search');
    }

    #[Computed]
    public function rows()
    {
        return Recording::with('user', 'requestable:id,company,reference,car_number,car_type')->whereHasMorph(
            'requestable',
            [CarRequest::class]
        )->when($this->date, function ($query) {
            $query->whereDate('created_at', $this->date);
        })->latest('id')->paginate(10);
    }



    public function recordSecurityCheck()
    {

        $this->validate();
        $item = CarRequest::findOrFail($this->car_request_id);
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
        $carRequests = CarRequest::select('id', 'status', 'reference', 'created_at', 'expire_at', 'car_number')
            ->where('status', MaterialRequestStatus::Approved)->get();

        return view('livewire.car-request.car-request-check-in', compact('departments', 'carRequests'));
    }
}
