<?php

declare(strict_types=1);

namespace App\Livewire\Forms;

use App\Enum\RoleEnum;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Livewire\Attributes\Validate;
use Livewire\Form;

use function to_route;

final class UserForm extends Form
{
    public ?User $user;

    #[Validate('required|string')]
    public string $name = '';

    #[Validate('required|string')]
    public string $poste = '';

    #[Validate('required|string|max:10')]
    public string $contact = '';

    #[Validate('required|string|max:12|unique:users')]
    public string $badge_number = '';

    #[Validate('required|string|email|max:255|unique:users')]
    public string $email = '';

    #[Validate('required|string|exists:departments,id')]
    public $department_id = '';

    #[Validate(['required', new Enum(RoleEnum::class)])]
    public string $role = '';

    #[Validate(['nullable', new Enum(RoleEnum::class)])]
    public $delegated_role = null;

    public function setUser(User $user): void
    {
        $this->user = $user;

        $this->name = $user->name;
        $this->email = $user->email;
        $this->poste = $user->poste;
        $this->badge_number = $user->badge_number;
        $this->contact = $user->contact;
        $this->role = $user->role->value;
        $this->department_id = $user->department_id;
        $this->delegated_role = $user->delegated_role;
    }

    public function store()
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['required', 'string', 'max:10', 'unique:users'],
            'badge_number' => ['required', 'string', 'max:12', 'unique:users'],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'poste' => ['required', 'string', 'max:100'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);
        $item = User::create($this->only(['name', 'email', 'role', 'department_id', 'poste', 'badge_number', 'contact']));
        $item->notify(new UserNotification($item));
        $this->reset();
        flash()->success('User added successfully');

        return to_route('user.index');
    }

    public function update(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->user->id)],
            'contact' => ['required', 'string', 'max:10', Rule::unique('users', 'contact')->ignore($this->user->id)],
            'badge_number' => ['required', 'string', 'max:12', Rule::unique('users', 'badge_number')->ignore($this->user->id)],
            'name' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'poste' => ['required', 'string', 'max:100'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
        ]);

        $this->user->update($this->only(['name', 'email', 'role', 'department_id', 'poste', 'badge_number', 'contact']));
        if ($this->delegated_role) {
            $this->user->delegateRole($this->delegated_role);
        } else {
            $this->user->revokeDelegatedRole();
        }

        if ($this->user->wasChanged('email')) {
            $this->user->notify(new UserNotification($this->user));
        }
        flash()->success('User updated successfully');
    }
}
