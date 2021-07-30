<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Contracts\Auth\StatefulGuard;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Fortify\Contracts\RegisterResponse;
use Laravel\Fortify\Contracts\LoginViewResponse;

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

        // 先查詢是否為已建立的使用者

        if (!$user = User::whereEmail($googleUser->getEmail())->first()) {
            return Inertia::render('Auth/Login', [
                'flash' => [
                    'banner' => '登入失敗，尚未建立的使用者。請聯絡管理員。'
                ]
            ]);
            // event(new Registered($user = $creator->create([
            //     'email' => $googleUser->getEmail(),
            //     'name' => $googleUser->getName(),
            //     'provider_id' => $googleUser->getId(),
            // ])));
        }

        $this->guard->login($user);

        return app(RegisterResponse::class);
    }
}
