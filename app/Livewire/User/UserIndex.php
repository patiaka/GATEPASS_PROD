<?php

declare(strict_types=1);

namespace App\Livewire\User;

use App\Exports\UsersTemplateExport;
use App\Imports\UsersImport;
use App\Models\Department;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

#[Title('All users')]
final class UserIndex extends Component
{
    use WithFileUploads, WithPagination;

    public string $role = '';

    public string $status = '';

    public string $search = '';

    public string $department = '';

    public $import_file;

    public function ResetFilter(): void
    {
        $this->reset('department', 'role', 'search', 'status');
    }

    public function import()
    {
        $this->validate([
            'import_file' => 'required|file|mimes:xlsx,xls',
        ]);

        $import = new UsersImport();
        Excel::import($import, $this->import_file);

        $this->reset('import_file');

        if (! empty($import->errors)) {
            flash()->warning($import->imported.' user(s) imported. '.count($import->errors).' row(s) skipped (duplicate or invalid).');
        } else {
            flash()->success($import->imported.' user(s) imported successfully.');
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new UsersTemplateExport, 'template_users.xlsx');
    }

    public function invite_user(User $user)
    {
        $user->update([
            'password' => Hash::make('password'),
            'change_password' => false,
        ]);

        $user->notify(new UserNotification($user));

        flash('User invited successfuly');
    }

    public function toggleUserStatus(int $id, bool $status): void
    {
        if (Auth::user()->id === $id) {
            flash()->error('You cannot change the status of your own account.');
            return;
        }

        $row = User::find($id);

        if (! $row) {
            flash()->error('User not found.');
            return;
        }

        $row->update([
            'status' => $status,
        ]);
        
        flash()->success(
            $row->status
                ? 'User activated successfully'
                : 'User deactivated successfully'
        );
    }

    #[Computed]
    public function rows()
    {
        return User::with('department:id,name', 'roleAssignments')->when($this->search, function ($query) {
                $query->whereAny(['name', 'email'], 'like', '%'.$this->search.'%');
            })
            // Department
            ->when($this->department, function ($query) {
                $query->where('department_id', $this->department);
            })
            // Role
            ->when($this->role, function ($query) {
                $query->whereHas('roleAssignments', fn ($q) => $q->where('role', $this->role));
            })
            // Status
            ->when($this->status !== '', function ($query) {
                $query->where('status', boolval($this->status));
            })
            ->latest('id')->paginate(10)
        ;
    }

    public function render()
    {
        $departments = Department::select('name', 'id')->get();

        return view('livewire.user.user-index', compact('departments'));
    }
}
