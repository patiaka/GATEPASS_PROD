<?php

declare(strict_types=1);

namespace App\Livewire\MaterialRequest;

use App\Exports\RecordingExport;
use App\Helper\WithFilter;
use App\Models\Department;
use App\Models\MaterialRequest;
use App\Models\Recording;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Check material request')]
final class MaterialRequestCheckIn extends Component
{
    use WithFilter;

    public string $gate = '';

    public string $action = '';

    public function ResetFilter(): void
    {
        $this->reset('department', 'gate', 'action', 'search', 'debut', 'fin');
    }

    public function export()
    {
        return (new RecordingExport($this->baseQuery(), 'material'))->download('material-recordings.xlsx');
    }

    public function baseQuery()
    {
             return Recording::with('user', 'requestable:id,company,reference,user_id,person_out_id,person_out_name', 'requestable.user.department:id,name', 'requestable.person_out:id,name')->whereHasMorph(
            'requestable',
            [MaterialRequest::class]
        )
            ->when($this->gate, function ($query) {
                $query->where('gate', $this->gate);
            })
            ->when($this->action, function ($query) {
                $query->where('action', $this->action);
            })
            ->when($this->debut, function ($query) {
                $query->whereDate('created_at', '>=', $this->debut);
            })->when($this->fin && $this->debut, function ($query) {
                $query->wherebetween('created_at', [$this->debut, $this->fin]);
            })->latest('id');
    }

    #[Computed]
    public function rows()
    {
        return $this->baseQuery()->paginate();
    }

    public function render()
    {

        $departments = Department::select('id', 'name')->get();

        return view('livewire.material-request.material-request-check-in', compact('departments'));
    }
}
