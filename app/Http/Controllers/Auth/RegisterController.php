<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    // 회원가입 뷰 반환
    public function showRegistrationForm(){
        return view('auth.register');
    }
    // 유효성 검사
    public function register(Request $request){
        $validated = $request->validate([
            'name' => 'required|max:16',
            'email' => 'required|email|unique:users|max:30',
            'password' => [Password::default()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        auth()->login($user);

        event(new Registered($user));

        return to_route('verification.notice');
    }
}
