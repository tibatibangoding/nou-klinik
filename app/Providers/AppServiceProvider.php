<?php

namespace App\Providers;

use App\Models\Klinik;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        $klinik = Klinik::where('id', 1)->first();
        View::share('klinik', $klinik);
        // $this->app['request']->server->set('HTTPS', 'on');
        // URL::forceScheme('https');
    }
}
