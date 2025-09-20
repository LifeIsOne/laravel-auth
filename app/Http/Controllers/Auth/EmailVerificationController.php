<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use http\Env\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class EmailVerificationController extends Controller
{
    // 이메일 인증
    public function verify(EmailVerificationRequest $request, int $id, string $hash){
        $request->fulfill();

        return redirect()->to(RouteServiceProvider::HOME);
    }

    // 이메일 인증되지 않은 사용자
    public function notice(){
        return view('auth.verify-mail');
    }

    // 이메일 재인증
    public function send(Request $request){
        $user = $request->user();

        $user->sendEmailVerificationNotification();

        return back();
    }
}
