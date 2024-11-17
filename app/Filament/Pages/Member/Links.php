<?php

/*
|--------------------------------------------------------------------------
| List of Members Shortened Links
|--------------------------------------------------------------------------
|
|
*/

namespace App\Filament\Pages\Member;

use Filament\Pages\Page;

class Links extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.member.links';

    protected static ?string $slug = 'member/links';


}

