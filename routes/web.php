<?php

declare(strict_types=1);

use App\Enum\RoleEnum;
use App\Http\Controllers\DocumentController;
use App\Livewire\CarRequest\CarRequestCheckIn;
use App\Livewire\CarRequest\CarRequestCheckInCreate;
use App\Livewire\CarRequest\CarRequestCreate;
use App\Livewire\CarRequest\CarRequestIndex;
use App\Livewire\CarRequest\CarRequestPending;
use App\Livewire\CarRequest\CarRequestShow;
use App\Livewire\CarRequest\CarRequestUpdate;
use App\Livewire\Audit\AuditLog;
use App\Livewire\Dashboard;
use App\Livewire\GateConsole;
use App\Livewire\MaterialGateConsole;
use App\Livewire\Department\DepartmentCreate;
use App\Livewire\Department\DepartmentEdit;
use App\Livewire\Department\DepartmentIndex;
use App\Livewire\MaterialRequest\MaterialRequestCheckIn;
use App\Livewire\MaterialRequest\MaterialRequestCheckInCreate;
use App\Livewire\MaterialRequest\MaterialRequestCreate;
use App\Livewire\MaterialRequest\MaterialRequestIndex;
use App\Livewire\MaterialRequest\MaterialRequestPending;
use App\Livewire\MaterialRequest\MaterialRequestShow;
use App\Livewire\MaterialRequest\MaterialRequestUpdate;
use App\Livewire\Reports\OffsiteReport;
use App\Livewire\Settings\SettingsIndex;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserIndex;
use App\Livewire\User\UserPassChange;
use App\Livewire\User\UserUpdate;
use Illuminate\Support\Facades\Route;

// Bascule de langue (FR / EN) — mémorisée en session
Route::get('locale/{locale}', function (string $locale) {
    if (in_array($locale, App\Http\Middleware\SetLocale::SUPPORTED, true)) {
        session(['locale' => $locale]);
    }

    return back();
})->name('locale.switch');

Route::middleware(['auth'])->group(function () {
    // Seuls HOD, GM et Admin
    Route::middleware('role:' . RoleEnum::HOD->value . ',' . RoleEnum::GM->value . ',' . RoleEnum::ADMIN->value . ',' . RoleEnum::DIRECTOR->value)
        ->group(function () {
            Route::get('material/request/pending', MaterialRequestPending::class)->name('material.pending');
            Route::get('car/request/pending', CarRequestPending::class)->name('car.pending');
        });

    // Admin uniquement
    Route::middleware('role:' . RoleEnum::ADMIN->value)->group(function () {
        Route::get('user', UserIndex::class)->name('user.index');
        Route::get('user/{user}/edit', UserUpdate::class)->name('user.edit');
        Route::get('user/create', UserCreate::class)->name('user.create');
        Route::get('department', DepartmentIndex::class)->name('department.index');
        Route::get('department/create', DepartmentCreate::class)->name('department.create');
        Route::get('department/{department}/edit', DepartmentEdit::class)->name('department.edit');
        Route::get('settings', SettingsIndex::class)->name('settings.index');
        Route::get('audit/log', AuditLog::class)->name('audit.index');
    });

    // Check-in : listes visibles par Admin, GM, Security (lecture)
    Route::middleware('role:' . RoleEnum::Security->value . ',' . RoleEnum::ADMIN->value . ',' . RoleEnum::GM->value)->group(function () {
        Route::get('material/request/check/in', MaterialRequestCheckIn::class)->name('material.check');
        Route::get('car/request/check/in', CarRequestCheckIn::class)->name('car.check');
    });

    // Check-in : enregistrement réservé à Admin et Security (GM ne fait que consulter)
    Route::middleware('role:' . RoleEnum::Security->value . ',' . RoleEnum::ADMIN->value)->group(function () {
        Route::get('car/request/check/in/create', CarRequestCheckInCreate::class)->name('car.check_create');
        Route::get('material/request/check/in/create', MaterialRequestCheckInCreate::class)->name('material.check_create');

        // Poste de garde : check-in express (tablette)
        Route::get('gate/console', GateConsole::class)->name('gate.console');
        Route::get('gate/console/material', MaterialGateConsole::class)->name('material.gate.console');
    });

    // Rapports : Admin, GM, Security
    Route::middleware('role:' . RoleEnum::ADMIN->value . ',' . RoleEnum::GM->value . ',' . RoleEnum::Security->value)->group(function () {
        Route::get('reports/offsite', OffsiteReport::class)->name('reports.offsite');
    });
    // Routes accessibles à tous
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('material/request/create', MaterialRequestCreate::class)->name('material.create');
    Route::get('car/request/create', CarRequestCreate::class)->name('car.create');
    Route::get('material/request', MaterialRequestIndex::class)->name('material.index');
    Route::get('car/request', CarRequestIndex::class)->name('car.index');
    Route::get('car/request/{CarRequest}/show', CarRequestShow::class)->name('car.show');
    Route::get('material/request/{MaterialRequest}/show', MaterialRequestShow::class)->name('material.show');
    Route::get('material/request/{MaterialRequest}/edit', MaterialRequestUpdate::class)->name('material.edit');
    Route::get('car/request/{CarRequest}/edit', CarRequestUpdate::class)->name('car.edit');
    Route::resource('document', DocumentController::class)->only('edit', 'update');
    Route::get('user/pass', UserPassChange::class)->name('user.pass');
});

require __DIR__ . '/auth.php';
