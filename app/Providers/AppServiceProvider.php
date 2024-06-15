<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Support\Assets\AlpineComponent;
use Filament\Support\Facades\FilamentAsset;
use Illuminate\Database\Eloquent\Model;

;

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
        Model::unguard();
        
        FilamentAsset::register([
            AlpineComponent::make('pdf-component', __DIR__ . '/../../resources/js/dist/components/pdf-component.js'),
        ]);
    }
}
