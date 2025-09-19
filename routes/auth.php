<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Auth\RegisterController::class)
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/register', 'showRegistrationForm')
                ->name('register');
            Route::post('/register', 'register');
        });
    });
