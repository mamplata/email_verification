<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/user', function () {
    return view('user');
});


Route::get('/translate-page', function () {
    return view('translate');
});

Route::get('/translate', function () {
    $keys = request()->input('keys', []); // Fetch keys array
    $locale = request()->input('locale', config('app.locale')); // Get locale or use default

    if (!is_array($keys)) {
        return response()->json(['error' => 'Invalid keys format'], 400);
    }

    // Temporarily set locale
    app()->setLocale($locale);

    $translations = [];
    foreach ($keys as $key) {
        $translations[$key] = __($key);
    }

    return response()->json($translations);
});

Route::get('/table-page', function () {
    return view('table');
});
