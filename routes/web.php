<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\ListController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, "loginView"])->name('loginView');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

Route::get('/tests', [TestsController::class, 'test'])->name('test');
Route::get('/register', [AuthController::class, 'registerView'])->name('registerView');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['auth'])->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');

    // Admin
    Route::middleware(['admin'])->group(function () {
        Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

        // Board
        Route::get('/admin/edit/board/{id}', [BoardController::class, 'edit'])->name('board.edit');
        Route::patch('/admin/update/board/{id}', [BoardController::class, 'update'])->name('board.update');
        Route::delete('/admin/delete/board/{id}', [BoardController::class, 'delete'])->name('board.delete');
        Route::post('/admin/create/board', [BoardController::class, 'create'])->name('board.create');

        // User
        Route::get('/admin/edit/user/{id}', [UserController::class, 'edit'])->name('user.edit');
        Route::patch('/admin/update/user/{id}', [UserController::class, 'update'])->name('user.update');
        Route::delete('/admin/delete/user/{id}', [UserController::class, 'delete'])->name('user.delete');
        Route::post('/admin/create/user', [AuthController::class, 'register'])->name('register');
    });

    Route::get('/list/{id}', [ListController::class, 'index'])->name('list.index');

    // Auth
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
