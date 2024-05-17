<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton('cart', function ($app) {
            return new \App\Services\CartService();
        });
    }
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
    }
}
