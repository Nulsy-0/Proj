<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TestsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, "loginView"])->name('loginView');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::get('/tests', [TestsController::class, 'test'])->name('test');

Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/register', [AuthController::class, 'registerView'])->name('registerView');
    Route::post('/register', [AuthController::class, 'register'])->name('register');

    // Admin
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
});
