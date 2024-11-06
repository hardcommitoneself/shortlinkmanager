<?php

/*
|--------------------------------------------------------------------------
| List of URL Shorteners
|--------------------------------------------------------------------------
|
| Name, CPM, API Key, Priority, Views, Enable/Disable
|
*/

namespace App\Filament\Pages\Website;

use Filament\Pages\Page;

class Shorteners extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static string $view = 'filament.pages.website.shorteners';

    protected static ?string $navigationGroup = 'Websites';

    protected static ?int $navigationSort = 2;
}

