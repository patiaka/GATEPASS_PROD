<?php

declare(strict_types=1);

namespace App\Livewire\CarRequest;

use App\Helper\ApproveAction;
use App\Helper\WithFilter;
use App\Helper\WithSorting;
use App\Models\CarDriver;
use App\Models\CarRequest;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Title('All vehicle request')]
final class CarRequestIndex extends Component
{
    use ApproveAction, WithFilter, WithSorting;

    #[Url(as: 'by_status')]
    public ?string $by_status = null;

    public $car;

    public function ResetFilter(): void
    {
        $this->reset('department', 'status', 'search', 'by_status', 'period', 'debut', 'fin', 'sortField', 'sortDirection');
    }

    #[Computed]
    public function rows()
    {
        $auth = Auth::user();

        $query = CarRequest::with('user', 'user.department', 'hodApproval', 'gmApproval')
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
            ->tap(fn ($query) => $this->applyPeriod($query))
        ;

        $sortable = [
            'reference' => 'reference',
            'date' => 'created_at',
            'company' => 'company',
            'status' => 'status',
            'requestor' => fn ($q, $dir) => $q->orderBy(
                User::select('name')->whereColumn('users.id', 'car_requests.user_id'),
                $dir
            ),
            'department' => fn ($q, $dir) => $q->orderBy(
                Department::select('name')->whereIn(
                    'departments.id',
                    User::select('department_id')->whereColumn('users.id', 'car_requests.user_id')
                ),
                $dir
            ),
        ];

        return $this->applySort($query, $sortable, fn ($q) => $q->latest('id'))->paginate(10);
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
