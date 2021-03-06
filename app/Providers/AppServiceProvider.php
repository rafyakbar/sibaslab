<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /**
         * set locale Indonesia secara global pada class Carbon
         */
        \Carbon\Carbon::setlocale('id');

        /**
         * set max execution time 3 jam
         */
        ini_set('max_execution_time', 10800);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
