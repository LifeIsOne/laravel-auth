<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Rules\Password as PasswordRule;

class RegisterController extends Controller
{
    // 회원가입 뷰 반환
    public function showRegistrationForm(){
        return view('auth.register');
    }
    // 유효성 검사
    public function register(Request $request){
        $request->validate([
            'name' => 'required|max:16',
            'email' => 'required|email|unique:users|max:30',
            'password' => [new PasswordRule()],
        ]);
    }
}
