<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\UserMenuItem;
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
        Filament::serving(function () {
            Filament::registerUserMenuItems([
                UserMenuItem::make()
                    ->label('Shortener Settings')
                    ->url('/member/dashboard')
                    //->url(route('filament.pages.member.dashboard'))
                    ->icon('heroicon-o-cog-6-tooth'),
                // ...
            ]);
        });
    }
}
