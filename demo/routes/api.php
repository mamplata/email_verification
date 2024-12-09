<?php

use App\Http\Controllers\ItemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use Illuminate\Contracts\Session\Session;

Route::middleware(['auth.token'])->group(function () {
    Route::get('/items', [ItemController::class, 'index'])->name('items.index');
});
