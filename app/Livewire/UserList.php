<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\Compagnie;
use App\Models\Department;
use App\Imports\UsersImport;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use App\Exports\UsersTemplateExport;
use App\Livewire\Forms\UserForm;
use Livewire\Attributes\Locked;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class UserList extends Component
{
    use WithPagination, WithFileUploads;

    public string $role = "";
    public string $search = "";
    public string $department = "";
    public $import_file;
    public UserForm $form;

    public function ResetFilter(): void
    {
        $this->reset('department', 'role', 'search');
    }

    public function save()
    {
        $this->form->store();
    }

    public function import()
    {
        $this->validate([
            'import_file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new UsersImport();
        Excel::import($import, $this->import_file);

        if (!empty($import->errors)) {
            return back()->withErrors($import->errors);
        }

        flash('User exported successfuly');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(new UsersTemplateExport, 'template_users.xlsx');
    }

    public function delete(int $id): void
    {
        $row = User::find($id);

        if (!$row) {
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
            $query->whereAny(['name', 'email'], 'like', '%' . $this->search . '%');
        })->when($this->department, function ($query) {
            $query->where('department_id', $this->department);
        })->when($this->role, function ($query) {
            $query->where('role', $this->role);
        })->latest('id')->paginate(10);
    }
    public function render()
    {
        $departments = Department::select('name', 'id')->get();
        return view('livewire.user-list', compact('departments'));
    }
}
