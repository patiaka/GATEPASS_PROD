<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

final class UserForm extends Form
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
        $item = User::create($this->only(['name', 'email', 'role', 'department_id', 'poste']));
        $item->notify(new UserNotification($item));
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

        if ($this->user->wasChanged('email')) {
            $this->user->notify(new UserNotification($this->user));
        }
        flash()->success('User updated successfully');
    }
}
