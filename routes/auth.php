<?php

use Illuminate\Support\Facades\Route;

Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function () {
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
            Route::get('/verify', 'notice')
                ->name('notice');
            Route::get('/verify/{id}/{hash}', 'verify')
                ->middleware('signed')
                ->name('verify');
            Route::post('/verification-notification', 'send')
                ->name('send');
        });
    });
});

// 로그인컨트롤러 라우트 그룹
Route::controller(\App\Http\Controllers\Auth\LoginController::class)->group(function() {
    // 로그인하지 않은 사용자만 접근 가능
    Route::middleware('guest')->group(function () {
        // 로그인 폼 뷰
        Route::get('/login', 'showLoginForm')
            ->name('login');
        // 로그인 폼 제출
        Route::post('/login', 'login');
    });
    // 로그아웃 처리 라우트
    Route::post('/logout', 'logout')
        ->name('logout')
        ->middleware('auth');   // 로그인된 사용자만
});

// OAuth 로그인 라우트 그룹
Route::controller(\App\Http\Controllers\Auth\SocialLoginController::class)->group(function () {
    // 게스트만 접근
    Route::middleware('guest')->name('login.')->group(function() {
        // `{procider}`로 리다이렉트
        Route::get('/login/{provider}', 'redirect')->name('social');
        // 로그인 후 인증 서버에서 콜백 라우트
        Route::get('/login/{provider}/callback', 'callback')->name('social.callback');
    });
});
