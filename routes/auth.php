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
// 이메일 인증 라우트
Route::controller(\App\Http\Controllers\Auth\EmailVerificationController::class)->group(function () {
    Route::name('verification.')->prefix('/email')->group(function () {
        Route::middleware('auth')->group(function () {
            Route::get('/verify', 'create')
                ->name('notice');
            Route::get('/verify/{id}/{hash}', 'update')
                ->name('verify')
                ->middleware('signed');
            Route::post('/verification-notification', 'store')
                ->name('send');
        });
    });
});
