<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;

class AuthController extends Controller
{
    public function __construct(StatefulGuard $guard)
    {
        $this->guard = $guard;
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(CreatesNewUsers $creator)
    {
        $googleUser = Socialite::driver('google')->user();

        if (!$user = User::whereProviderId($googleUser->getId())->first()) {
            event(new Registered($user = $creator->createByProvider([
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
                'provider_id' => $googleUser->getId(),
            ])));
        }

        $this->guard->login($user);

        return app(RegisterResponse::class);
    }
}
