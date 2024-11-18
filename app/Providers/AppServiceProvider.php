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
                    ->label('Dashboard')
                    ->url('/member/dashboard')
                    //->url(route('filament.pages.member.dashboard'))
                    ->icon('heroicon-o-window'),
                UserMenuItem::make()
                    ->label('Settings')
                    ->url('/member/profile/settings')
                    //->url(route('filament.pages.member.profile.settings'))
                    ->icon('heroicon-o-cog-6-tooth'),
            ]);
        });
    }
}
