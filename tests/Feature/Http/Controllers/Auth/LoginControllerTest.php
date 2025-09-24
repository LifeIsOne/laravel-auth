<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    // 로그인 뷰 반환 테스트
    public function testReturnsLoginView()
    {
        $this->get(route('login')) // '/login' 라우트로 GET요청
            ->assertOk()    // 200인지?
            ->assertViewIs('auth.login');   // 반환뷰가 auth.login인지?
    }
    // 로그인 성공 테스트
    public function testLoginForValidCredentials() {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password',
        ])
        ->assertRedirect();
        // 사용자가 인증됐는지 확인
        $this->assertAuthenticated();
    }
    // 로그인 실패 테스트
    public function testFailToLoginForValidCredentials() {
        $user = User::factory()->create();

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => $this->faker->password(8),
        ])
        ->assertRedirect()
        ->assertSessionHasErrors('failed');
        // 게스트(로그인 하지 않은 사용자)인지 확인
        $this->assertGuest();
    }

    // 로그아웃 테스트
    public function testLogout() {
        $user = User::factory()->create(); // 테스트용 user 생성

        $this->actingAs($user)              // 생성한 user로 로그인 상태
            ->post(route('logout')) // '/logout'라우트로 POST요청
            ->assertRedirect(RouteServiceProvider::HOME);   // 리다이렉트인지 확인

        $this->assertGuest();   // 현제 세션의 로그인 상태 확인
    }
}
