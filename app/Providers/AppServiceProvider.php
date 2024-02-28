<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        FilamentColor::register([
            'Amber' => Color::hex('#000000'),
        ]);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    { 
        FilamentColor::register([
            'africen' => Color::hex('#000000'),
        ]);
    }
}
