<?php

namespace App\Livewire\Forms;

use Livewire\Form;
use App\Models\User;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;

class UserForm extends Form
{
    public ?User $user;

    #[Validate('required|string')]
    public string $name = '';
    #[Validate('required|string')]
    public string $poste = '';
    #[Validate('required|string|email|max:255|unique:users')]
    public string $email = '';
    #[Validate('required|integer|exists:departments,id')]
    public string $department_id = '';
    #[Validate('required|in:User,General Manager,Head of Department,Administrator')]
    public string $role = '';

    public function setUser(User $user): void
    {
        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->poste = $user->poste;
        $this->role = $user->role->value;
        $this->department_id = $user->department_id;
    }

    public function store(): void
    {
        $this->validate();
        User::create($this->only(['name', 'email', 'role', 'department_id', 'poste']));
        $this->reset();
        flash()->success('User added successfully');
    }

    public function update(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $this->user->update($this->only(['name', 'email', 'role', 'department_id', 'poste']));

        $this->reset();
        flash()->success('User updated successfully');
    }
}
