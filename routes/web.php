<?php

use App\Models\User;
use App\Enum\RoleEnum;
use App\Livewire\UserList;
use App\Livewire\CarRequest\Create;
use Illuminate\Support\Facades\Route;
use App\Livewire\MaterialRequest\Show;
use App\Livewire\MaterialRequest\Index;
use App\Http\Controllers\UserController;
use App\Livewire\MaterialRequest\Update;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\CompagnieController;
use App\Http\Controllers\CarRequestController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\MaterialRequestItemController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::middleware('role:' . RoleEnum::ADMIN->value)->group(function () {
        Route::resource('department', DepartmentController::class)->except('show');
        Route::resource('compagnie', CompagnieController::class)->except('show');
        Route::get('user', UserList::class)->name('user.index');
        Route::resource('material', MaterialRequestController::class)->only('destroy');
        Route::resource('car', CarRequestController::class)->only('destroy');
    });
    Route::get('material/request', App\Livewire\MaterialRequest\Index::class)->name('material.index');
    Route::get('material/request/create', App\Livewire\MaterialRequest\Create::class)->name('material.create');
    Route::get('material/request/{material}/edit', App\Livewire\MaterialRequest\Update::class)->name('material.edit');
    Route::get('material/request/{material}/show', App\Livewire\MaterialRequest\Show::class)->name('material.show');

    Route::get('car/request', App\Livewire\CarRequest\Index::class)->name('car.index');
    Route::get('car/request/create', App\Livewire\CarRequest\Create::class)->name('car.create');
    Route::get('car/request/{car}/edit', App\Livewire\CarRequest\Update::class)->name('car.edit');
    Route::get('car/request/{car}/show', App\Livewire\CarRequest\Show::class)->name('car.show');


    Route::resource('request/material/item', MaterialRequestItemController::class)->except('index', 'create');
    Route::resource('document', DocumentController::class)->only('edit', 'update', 'destroy');
});


require __DIR__ . '/auth.php';
