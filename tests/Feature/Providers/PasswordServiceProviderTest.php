<?php

namespace Tests\Feature\Providers;

use Illuminate\Contracts\Validation\UncompromisedVerifier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Tests\TestCase;

class PasswordServiceProviderTest extends TestCase
{
    // Production 환경
    public function testPasswordRuleInProduction(): void
    {
        // 앱 환경 바인딩 - production
        $this->app->bind('env', function () {
            return 'production';
        });
        // UncompromisedVerifier mock 생성
        $this->mock(UncompromisedVerifier::class, function ($mock) {
            $mock->shouldReceive('verify')
                ->once()
                ->andReturn(true);
        });
        // 실패 테스트
        $validator = Validator::make(['password' => 'password'], [
            'password' => Password::default(),
        ]);
        $this->assertFalse(
            $validator->passes()
        );
        // 성공 테스트
        $validator->setData(['password' => 'p@ssW0rd']);
        $this->assertTrue(
            $validator->passes()
        );
    }
    // Dev 환경
    public function testPasswordRule(): void
    {
        $validator = Validator::make(['password' => 'password'], [
            'password' => Password::default(),
        ]);
        $this->assertTrue(
            $validator->passes()
        );
    }
}
