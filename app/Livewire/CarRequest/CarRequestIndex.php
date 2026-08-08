<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Helper\ApproveAction;
use App\Helper\WithFilter;
use App\Models\CarDriver;
use App\Models\CarRequest;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('All vehicle request')]
final class CarRequestIndex extends Component
{
    use ApproveAction, WithFilter;

    #[Url(as: 'by_status')]
    public ?string $by_status = null;

    public $car;

    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();

        return CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
            // Visibilité par rôle (même logique que le Dashboard)
            ->visibleTo($auth)
            // Filters
            ->when($this->department, function ($query) {
                $users = Department::with('users')->find($this->department)?->users ?? collect();
                $query->whereIn('user_id', $users->pluck('id'));
            })
            ->when($this->by_status, function ($query) {
                $query->where('status', $this->by_status);
            })
            ->when($this->search, function ($query) {
                $query->whereAny(['reference', 'status'], 'like', '%'.$this->search.'%');
            })
            ->latest('id')
            ->paginate(10)
        ;
    }

    public function delete(int $id): void
    {
        $row = CarRequest::find($id);

        if (! $row) {
            flash()->error('Car request not found.');

            return;
        }

        Gate::authorize('delete-request', $row);

        CarDriver::where('car_request_id', $row->id)->delete();

        $row->delete();
        flash()->success('Car request deleted with success');
    }

    public function render()
    {
        $auth = Auth::user();
        $departments = $auth->isAdmin() ? Department::select('id', 'name')->get() : [];

        return view('livewire.car-request.car-request-index', compact('departments'));
    }
}
