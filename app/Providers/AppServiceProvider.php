<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use App\Http\Resources\WebhookReceiver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (App::environment('production')) {
            app('request')->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        WebhookReceiver::withoutWrapping();
    }
}
