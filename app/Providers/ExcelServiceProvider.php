<?php

namespace App\Providers;

use App\Services\Excel\Excel;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class ExcelServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('excel', function ($app, $data = null) {
            if (is_array($data)) {
                $data = collect($data);
            }

            return new Excel($data);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function provides()
    {
        return [Excel::class];
    }
}
