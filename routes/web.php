<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\MaterialRequestController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Livewire\UserList;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('user', UserList::class)->name('user.index');
Route::resource('user', UserController::class)->except('index');
Route::resource('department', DepartmentController::class)->except('show');
Route::resource('material-request', MaterialRequestController::class);
require __DIR__ . '/auth.php';
