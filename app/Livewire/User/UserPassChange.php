<?php

declare(strict_types=1);

namespace App\Livewire\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Edit user password')]
final class UserPassChange extends Component
{
    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function save()
    {
        try {
            $validated = $this->validate([
                'current_password' => ['required', 'string', 'current_password'],
                'password' => ['required', 'string', Password::min(8)
                    ->mixedCase()    // Must contain both uppercase and lowercase letters.
                    ->letters()      // Must contain at least one letter.
                    ->numbers()      // Must contain at least one number.
                    ->symbols()      // Must contain at least one symbol.
                    ->uncompromised(), 'confirmed'],
            ]);
        } catch (ValidationException $e) {
            $this->reset('current_password', 'password', 'password_confirmation');

            throw $e;
        }

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        $this->reset('current_password', 'password', 'password_confirmation');

        flash()->success('Password changed successfully');
    }
}
