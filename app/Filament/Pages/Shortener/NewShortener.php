<?php

namespace App\Filament\Pages\Shortener;

use Filament\Pages\Page;

class NewShortener extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string $view = 'filament.pages.shortener.new-shortener';

    protected static ?string $navigationGroup = 'Shortener';

    protected static ?int $navigationSort = 3;
}
