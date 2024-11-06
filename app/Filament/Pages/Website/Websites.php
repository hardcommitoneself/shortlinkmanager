<?php

/*
|--------------------------------------------------------------------------
| List of Websites, URL and API Key
|--------------------------------------------------------------------------
|
| 1. Form with Website Name & URL to create a new entry
|       1a. API Key and URL should be unique
| 
| 2. Table listing Website, URL and Generrate API Key
|       Ex: My Site, https://example.com, TXkgU2l0ZSwgaHR0cHM6Ly9leGFtcGxlLmNvbQ
|
*/

namespace App\Filament\Pages\Websites;

use Filament\Pages\Page;

class Websites extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-arrow-trending-up';

    protected static string $view = 'filament.pages.website.websites';

    protected static ?string $navigationGroup = 'Websites';

    protected static ?int $navigationSort = 1;
}

