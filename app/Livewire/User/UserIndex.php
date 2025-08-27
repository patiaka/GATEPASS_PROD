<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Exports\UsersTemplateExport;
use App\Imports\UsersImport;
use App\Models\Department;
use App\Models\User;
use App\Notifications\UserNotification;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

final class UserIndex extends Component
{
    use WithFileUploads, WithPagination;

    public string $role = '';

    public string $search = '';

    public string $department = '';

    public $import_file;

    public function ResetFilter(): void
    {
        $this->reset('department', 'role', 'search');
    }

    public function import()
    {
        $this->validate([
            'import_file' => 'required|mimes:xlsx,xls',
        ]);

        $import = new UsersImport();
        Excel::import($import, $this->import_file);

        if (! empty($import->errors)) {
            return back()->withErrors($import->errors);
        }

        flash('User exported successfuly');
    }

    public function invite_user(User $user)
    {
        $user->notify(new UserNotification($user));

        flash('User invited successfuly');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new UsersTemplateExport, 'template_users.xlsx');
    }

    public function delete(int $id): void
    {
        $row = User::find($id);

        if (! $row) {
            flash()->error('User not found.');

            return;
        }

        $row->delete();
        flash()->success('User deleted with success');
    }

    #[Computed]
    public function rows()
    {
        return User::with('department:id,name')->when($this->search, function ($query) {
            $query->whereAny(['name', 'email'], 'like', '%'.$this->search.'%');
        })->when($this->department, function ($query) {
            $query->where('department_id', $this->department);
        })->when($this->role, function ($query) {
            $query->where('role', $this->role);
        })->latest('id')->paginate(10);
    }

    public function render()
    {
        $departments = Department::select('name', 'id')->get();

        return view('livewire.user.user-index', compact('departments'));
    }
}
