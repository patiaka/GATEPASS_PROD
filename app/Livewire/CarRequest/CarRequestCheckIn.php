<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Enum\MaterialRequestStatus;
use App\Helper\WithFilter;
use App\Models\CarRequest;
use App\Models\Department;
use Livewire\Attributes\Computed;
use Livewire\Component;

use function compact;

final class CarRequestCheckIn extends Component
{
    use WithFilter;

    public string $date = '';

    public function ResetFilter(): void
    {
        $this->reset('department', 'date', 'search');
    }

    #[Computed]
    public function rows()
    {
        return CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
            ->where('status', MaterialRequestStatus::Approved)
            ->when($this->department, function ($query) {
                $query->whereIn('department_id', $this->department);
            })->when($this->date, function ($query) {
                $query->whereDate('created_at', $this->date);
            })->when($this->search, function ($query) {
                $query->whereAny(['reference'], 'like', '%'.$this->search.'%');
            })
            ->latest('id')->paginate(10);
    }

    public function render()
    {
        $departments = Department::select('id', 'name')->get();

        return view('livewire.car-request.car-request-check-in', compact('departments'));
    }
}
