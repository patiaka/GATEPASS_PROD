<?php

use App\Models\User;
use App\Enum\RoleEnum;
use App\Livewire\UserList;
use App\Livewire\Dashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Livewire\Department\DepartmentEdit;
use App\Http\Controllers\DocumentController;
use App\Livewire\Department\DepartmentIndex;
use App\Http\Controllers\CompagnieController;
use App\Http\Controllers\CarRequestController;
use App\Http\Controllers\DepartmentController;
use App\Livewire\CarRequest\CarRequestPending;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\MaterialRequestItemController;
use App\Livewire\MaterialRequest\MaterialRequestPending;

Route::middleware(['auth'])->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');

    Route::middleware('role:' . RoleEnum::ADMIN->value)->group(function () {
        Route::get('user', UserList::class)->name('user.index');
        Route::resource('user', UserController::class)->except('index');
    });
    Route::get('department', DepartmentIndex::class)->name('department.index');
    Route::get('department/{department}/edit', DepartmentEdit::class)->name('department.edit');
    Route::get('material/request', App\Livewire\MaterialRequest\Index::class)->name('material.index');
    Route::get('material/request/create', App\Livewire\MaterialRequest\Create::class)->name('material.create');
    Route::get('material/request/{MaterialRequest}/edit', App\Livewire\MaterialRequest\Update::class)->name('material.edit');
    Route::get('material/request/{MaterialRequest}/show', App\Livewire\MaterialRequest\Show::class)->name('material.show');
    Route::get('material/request/{Material}/print', [MaterialRequestController::class, 'print'])->name('material.print');

    Route::get('car/request/pending', CarRequestPending::class)->name('car.pending');
    Route::get('material/request/pending', MaterialRequestPending::class)->name('material.pending');

    Route::get('car/request', App\Livewire\CarRequest\Index::class)->name('car.index');
    Route::get('car/request/create', App\Livewire\CarRequest\Create::class)->name('car.create');
    Route::get('car/request/{CarRequest}/edit', App\Livewire\CarRequest\Update::class)->name('car.edit');
    Route::get('car/request/{CarRequest}/show', App\Livewire\CarRequest\Show::class)->name('car.show');
    Route::get('car/request/{CarRequest}/print', [CarRequestController::class, 'print'])->name('car.print');

    Route::resource('request/material/item', MaterialRequestItemController::class)->except('index', 'create');
    Route::resource('document', DocumentController::class)->only('edit', 'update', 'destroy');
});


require __DIR__ . '/auth.php';
