<?php

namespace App\Providers;

use App\Services\Excel\Excel;
use Illuminate\Support\ServiceProvider;

class ExcelServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind('excel', function ($app, $data = null) {
            if (is_array($data)) {
                $data = collect($data);
            }

            return new Excel($data);
        });
    }
}
