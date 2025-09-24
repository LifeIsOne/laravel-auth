<?php

namespace Tests\Feature\Rules;

use App\Rules\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PasswordTest extends TestCase
{
    // 성공 테스트
    public function testAcceptsValidPassword (): void
    {
        // Validator(검사기) 생성, Password 적용
        $validator = Validator::make(
            ['password'=> 'p@ssW0rd'],  // 대•소문자, 특수문자, 숫자 포함
            ['password'=> new Password(),],
        );
        // true 인지 확인
        $this->assertTrue(
            $validator->passes()
        );
    }
    // 실패 테스트
    public function testRejectsInvalidPassword(): void {
        $validator = Validator::make(
            ['password'=> 'password'],
            ['password'=> new Password(),],
        );
        // false 인지 확인
        $this->assertFalse(
            $validator->passes()
        );
    }
}
