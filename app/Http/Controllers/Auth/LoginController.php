<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Providers\RouteServiceProvider;

class LoginController extends Controller
{
    // 로그인 뷰 반환
    public function showLoginForm() {
        return view('auth.login');
    }

    // 로그인 처리 메서드
    public function login(LoginRequest $request) {
        // 입력값 확인 후 로그인 시도
        if (! auth()->attempt($request->validated(), $request->boolean('remember'))) {
            // 로그인 실패 시 back() with 에러 메시지 전달
            return back()->withErrors([
                'failed' => __('auth.failed'),
            ]);
        }
        // 로그인 성공 리다이렉트 - intended() - 로그인 전 요청 URL
        return redirect()->intended();
    }

    // 로그아웃
    public function logout() {
        auth()->logout();   // 현재 사용자 로그아웃

        session()->invalidate();        // 세션(ID) 초기화
        session()->regenerateToken();   // CSRF 토큰 재생성
        // 리다이렉트 : HOME
        return redirect()->to(RouteServiceProvider::HOME);
    }
}
