<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\ValidateSignature;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EmailVerificationControllerTest extends TestCase
{
    // 이메일 인증 테스트
    public function testVerifyEmail() {
        // User 생성
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)  // 로그인 된 상태로 가정
            ->withoutMiddleware(ValidateSignature::class)   // 이메일 인증 URL 전자 서명 검증 미들웨어 제외
            ->get(route('verification.verify',[                 // 라우트 호출, id와 이메일 인증 해시 전달
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]))
            ->assertRedirect(RouteServiceProvider::HOME);   // 인증 후 리다이렉트 확인

        // 이메일 인증 완료 확인
        $this->assertTrue($user->hasVerifiedEmail());
    }
    // 이메일 인증되지 않음 테스트
    public function testReturnsVerifyEmailViewForUnverifiedUser() {
        $this->withoutMiddleware(Authenticate::class)
            ->get(route('verification.notice'))
            ->assertOk()
            ->assertViewIs('auth.verify-email');
    }
    // 이메일 재인증 테스트
    public function testSendEmailForEmailVerification() {
        // 알림 전송 - fake
        Notification::fake();

        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->post(route('verification.send'))
            ->assertRedirect();
        // 알림 전송 확인
        Notification::assertSentTo($user, VerifyEmail::class);
    }
}
