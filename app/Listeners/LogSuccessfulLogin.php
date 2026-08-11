<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Models\LoginLog;
use Illuminate\Auth\Events\Login;

final class LogSuccessfulLogin
{
    public function handle(Login $event): void
    {
        LoginLog::create([
            'user_id' => $event->user->getAuthIdentifier(),
            'user_name' => $event->user->name ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => mb_substr((string) request()->userAgent(), 0, 500),
        ]);
    }
}
