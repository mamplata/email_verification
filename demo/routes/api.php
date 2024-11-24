<?php

use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::get('/users/{id}/otc', [UserController::class, 'displayOTC']);
Route::get('/users/{id}/generate-otc', [UserController::class, 'generateOTC']);
Route::put('/users/{user}/password', [UserController::class, 'updatePassword']);

Route::get('/items', [ItemController::class, 'index'])->name('items.index');
Route::get('/search', [ItemController::class, 'search']);
