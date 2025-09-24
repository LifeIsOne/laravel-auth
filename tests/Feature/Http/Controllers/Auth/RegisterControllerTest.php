<?php

namespace Tests\Feature\Http\Controllers\Auth;

use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 뷰 반환 테스트
    public function testReturnRegisterView(){
        $this->get(route('register'))
            ->assertOk()
            ->assertViewIs('auth.register');
    }

    // 회원가입 테스트
    public function testUserRegistration(){
        // 이메일 전송 X
        Event::fake();

        $email = $this->faker->safeEmail;
        // 사용자 생성,  리다이렉트 검증
        $this->post(route('register'), [
            'name' => $this->faker->name(),
            'email' => $email,
            'password' => 'password',
        ])
        ->assertRedirect(route('verification.notice'));
        // 데이터베이스 검증
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);
        // User 생성 후 로그인 검증
        $this->assertAuthenticated();
        // 이메일 전송 대신 디스패치 검증
        Event::assertDispatched(Registered::class);
    }
}
