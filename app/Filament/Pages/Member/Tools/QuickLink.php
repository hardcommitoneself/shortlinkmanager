<?php

/*
|--------------------------------------------------------------------------
| Quick Link
|--------------------------------------------------------------------------
|
| Allows user to quickly redirect to destination link
| Quick link offers a very easy way to shorten links. You just need to copy the link below and replace replace "destinationlink.com" with your destination link.
| https://example.com/quick?api=Website-API-KEY&url=destinationlink.com
|
| https://get4links.com/member/tools/quick
| 
*/

namespace App\Filament\Pages\Member\Tools;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class QuickLink extends Page
{

    //protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $view = 'filament.pages.member.tools.quick-link';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?string $slug = 'member/tools/quick';

    protected static ?int $navigationSort = 1;

    public function mount()
    {
        abort_if(!Auth::user()->can('view quick-link'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view quick-link');
    }
}