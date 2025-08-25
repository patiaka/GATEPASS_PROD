<?php

use App\Models\User;
use App\Enum\RoleEnum;
use App\Livewire\User\UserIndex;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\CarRequest\CarRequestShow;
use App\Livewire\Department\DepartmentEdit;
use App\Http\Controllers\DocumentController;
use App\Livewire\CarRequest\CarRequestIndex;
use App\Livewire\Department\DepartmentIndex;
use App\Http\Controllers\CompagnieController;
use App\Livewire\CarRequest\CarRequestCreate;
use App\Livewire\CarRequest\CarRequestUpdate;
use App\Http\Controllers\CarRequestController;
use App\Http\Controllers\DepartmentController;
use App\Livewire\CarRequest\CarRequestPending;
use App\Http\Controllers\MaterialRequestController;
use App\Livewire\MaterialRequest\MaterialRequestShow;
use App\Livewire\MaterialRequest\MaterialRequestIndex;
use App\Http\Controllers\MaterialRequestItemController;
use App\Livewire\CarRequest\CarRequestDownload;
use App\Livewire\Department\DepartmentCreate;
use App\Livewire\MaterialRequest\MaterialRequestCreate;
use App\Livewire\MaterialRequest\MaterialRequestUpdate;
use App\Livewire\MaterialRequest\MaterialRequestPending;
use App\Livewire\User\UserCreate;
use App\Livewire\User\UserUpdate;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::middleware('role:' . RoleEnum::ADMIN->value)->group(function () {
        Route::get('user', UserIndex::class)->name('user.index');
        Route::get('user/{user}/edit', UserUpdate::class)->name('user.edit');
        Route::get('user/create', UserCreate::class)->name('user.create');

        Route::get('department', DepartmentIndex::class)->name('department.index');
        Route::get('department/create', DepartmentCreate::class)->name('department.create');
        Route::get('department/{department}/edit', DepartmentEdit::class)->name('department.edit');
    });
    Route::get('material/request', MaterialRequestIndex::class)->name('material.index');
    Route::get('material/request/create', MaterialRequestCreate::class)->name('material.create');
    Route::get('material/request/{MaterialRequest}/edit', MaterialRequestUpdate::class)->name('material.edit');
    Route::get('material/request/{MaterialRequest}/show', MaterialRequestShow::class)->name('material.show');
    Route::get('material/request/{Material}/print', [MaterialRequestController::class, 'print'])->name('material.print');
    Route::get('material/request/pending', MaterialRequestPending::class)->name('material.pending');

    Route::get('car/request/{CarRequest}/download', CarRequestDownload::class)->name('car.download');
    Route::get('car/request/pending', CarRequestPending::class)->name('car.pending');
    Route::get('car/request', CarRequestIndex::class)->name('car.index');
    Route::get('car/request/create', CarRequestCreate::class)->name('car.create');
    Route::get('car/request/{CarRequest}/edit', CarRequestUpdate::class)->name('car.edit');
    Route::get('car/request/{CarRequest}/show', CarRequestShow::class)->name('car.show');
    Route::get('car/request/{CarRequest}/print', [CarRequestController::class, 'print'])->name('car.print');

    Route::resource('request/material/item', MaterialRequestItemController::class)->except('index', 'create');
    Route::resource('document', DocumentController::class)->only('edit', 'update', 'destroy');
});


require __DIR__ . '/auth.php';
