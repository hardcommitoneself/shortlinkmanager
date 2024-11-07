<?php

/*
|--------------------------------------------------------------------------
| List of URL Shorteners
|--------------------------------------------------------------------------
|
| Name, CPM, API Key, Priority, Views, Enable/Disable
|
*/

namespace App\Filament\Pages\Shortener;

use Filament\Pages\Page;

class Shorteners extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static string $view = 'filament.pages.shortener.shorteners';

    protected static ?string $navigationGroup = 'Shortener';

    protected static ?int $navigationSort = 2;
}
