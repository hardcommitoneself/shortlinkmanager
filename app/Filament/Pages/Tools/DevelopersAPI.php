<?php

/*
|--------------------------------------------------------------------------
| Developers API
|--------------------------------------------------------------------------
|
| This page shows how to use cURL to create the shortened links
| https://get4links.com/member/tools/api
|
*/

namespace App\Filament\Pages\Tools;

use Filament\Pages\Page;

class DevelopersAPI extends Page
{

    protected static ?string $navigationIcon = '';

    protected static string $view = 'filament.pages.tools.developers-api';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?string $title = 'Developers API';

    protected static ?string $navigationLabel = 'Developers API';

    //protected ?string $subheading = 'Custom Page Subheading';

    protected static ?string $slug = 'member/tools/api';

    protected static ?int $navigationSort = 3;

}