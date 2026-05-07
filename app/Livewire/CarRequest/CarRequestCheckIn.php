<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Exports\RecordingExport;
use App\Helper\WithFilter;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\Recording;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

use function compact;

#[Title('Check Vehicle offsite request')]
final class CarRequestCheckIn extends Component
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
        return (new RecordingExport($this->baseQuery(), 'car'))->download('recordings.xlsx');
    }

    public function baseQuery()
    {
        return Recording::with('user', 'requestable:id,company,reference,car_number,car_type', 'car_driver:id,name,department_id', 'car_driver.department:id,name')->whereHasMorph(
            'requestable',
            [CarRequest::class]
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

        return view('livewire.car-request.car-request-check-in', compact('departments'));
    }
}
