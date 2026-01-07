<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('authenticate', [AuthController::class, 'login'])->name('authenticate');