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
use Illuminate\Support\Facades\Auth;

class Links extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.member.links';

    protected static ?string $slug = 'member/links';

    public function mount()
    {
        abort_if(! Auth::user()->can('view links'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view links');
    }
}
