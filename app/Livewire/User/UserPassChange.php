<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserPassChange extends Component
{
    public User $user;

    public string $current_password = '';
    public string $password = '';
    // public string $current_password = '';


    public function mount(User $user)
    {
        $this->user = $user;
        $this->form->setUser($user);
    }

    public function save()
    {
        $validated = $this->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $this->user()->update([
            'password' => Hash::make($validated['password']),
        ]);
    }
}
