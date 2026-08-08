<?php

declare(strict_types=1);

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Expire les demandes approuvées dont la date est dépassée.
// Exécuté par la tâche Windows "GatePass Scheduler" (schedule:run chaque minute).
Schedule::command('app:check-request-status')->hourly()->withoutOverlapping();
