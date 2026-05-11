<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Auth;

class UserLoginListener
{
    /**
     * Handle the event.
     */
    public function handle(Authenticated $event): void
    {
        /** @var \App\Models\User $user */
        $user = $event->user;

        if ($user->isBlocked()) {
            flash()->error('Your account is not active. Please contact the IT team.');
            Auth::logout();
        }
    }
}
