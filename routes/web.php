<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MaterialRequestController;
use App\Http\Controllers\MaterialRequestItemController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Livewire\MaterialRequestCreate;
use App\Livewire\MaterialRequestList;
use App\Livewire\MaterialRequestUpdate;
use App\Livewire\UserList;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('user', UserList::class)->name('user.index');
Route::get('material/request', MaterialRequestList::class)->name('material.index');
Route::get('material/request/create', MaterialRequestCreate::class)->name('material.create');
Route::get('material/request/{material}/edit', MaterialRequestUpdate::class)->name('material.edit');
Route::resource('user', UserController::class)->except('index');
Route::resource('department', DepartmentController::class)->except('show');
Route::resource('request/material', MaterialRequestController::class)->except('index', 'create', 'edit');
Route::resource('request/material/item', MaterialRequestItemController::class)->except('index', 'create');
require __DIR__ . '/auth.php';
