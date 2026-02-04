<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Midtrans\Config;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot()
{
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    \Midtrans\Config::$isProduction = config('midtrans.is_production');
    \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
    \Midtrans\Config::$is3ds = config('midtrans.is_3ds');

    // Pakai cara ini supaya tidak merah di VS Code
    \Midtrans\Config::$curlOptions = [
        CURLOPT_SSL_VERIFYPEER => false,
    ];
}
}
