<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::middleware(['role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'adminIndex'])->name('admin.dashboard')->middleware('auth');
});

Route::middleware(['role:user'])->group(function () {
    Route::get('/user/dashboard', [AuthController::class, 'userIndex'])->name('user.dashboard')->middleware('auth');
});
