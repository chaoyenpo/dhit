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
        $user = Socialite::driver('google')->user();

        if (!$user = User::whereProviderId($user->getId())->first()) {
            event(new Registered($user = $creator->createByProvider([
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'provider_id' => $user->getId(),
            ])));
        }

        $this->guard->login($user);

        return app(RegisterResponse::class);
    }
}
