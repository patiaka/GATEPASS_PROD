<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Livewire\UserList;

Route::get('/', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('user/index', UserList::class)->name('user.index');
Route::resource('user', UserController::class)->except('index');
require __DIR__ . '/auth.php';
