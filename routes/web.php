<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\MaterialRequestItemController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Livewire\MaterialRequest\Create;
use App\Livewire\MaterialRequest\Index;
use App\Livewire\MaterialRequest\Show;
use App\Livewire\MaterialRequest\Update;
use App\Livewire\UserList;

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('user', UserList::class)->name('user.index');

    Route::get('material/request', Index::class)->name('material.index');
    Route::get('material/request/create', Create::class)->name('material.create');
    Route::get('material/request/{material}/edit', Update::class)->name('material.edit');
    Route::get('material/request/{material}/show', Show::class)->name('material.show');

    Route::get('car/request', Index::class)->name('car.index');
    Route::get('car/request/create', Create::class)->name('car.create');
    Route::get('car/request/{car}/edit', Update::class)->name('car.edit');
    Route::get('car/request/{car}/show', Show::class)->name('car.show');

    Route::resource('user', UserController::class)->except('index');
    Route::resource('department', DepartmentController::class)->except('show');
    Route::resource('request/material/item', MaterialRequestItemController::class)->except('index', 'create');
});
require __DIR__ . '/auth.php';
