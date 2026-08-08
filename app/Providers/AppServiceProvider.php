<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
    public function boot(): void
    {
        // Vercel meneruskan request ke Laravel lewat http:// di belakang layar
        // walaupun user aksesnya pakai https://. Ini bikin Laravel generate
        // URL asset/route pakai http:// (mixed content warning di browser).
        // Paksa selalu pakai https:// kecuali di local.
        if (! $this->app->environment('local')) {
            URL::forceScheme('https');
        }
    }
}