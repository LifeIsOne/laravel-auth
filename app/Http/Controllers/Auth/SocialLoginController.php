<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Provider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{
    public function redirect(Provider $provider) {
        return Socialite::driver($provider->value)->redirect();
    }

    public function callback(Provider $provider) {
        $socialUser = Socialite::driver($provider->value)->user();
        $user = $this->register($socialUser);

        auth()->login($user);

        session()->socialite($provider, $socialUser->getEmail());

        return redirect()->intended();
    }

    public function register(SocialiteUser $socialiteUser) {
        $user = User::updateOrcreate([
            'email' => $socialiteUser->getEmail(),
        ], [
            'name' => $socialiteUser->getName(),
        ]);

        if ($user instanceof MustVerifyEmail && ! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        return $user;
    }
}
