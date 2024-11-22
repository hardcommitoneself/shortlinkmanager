<?php

/*
|--------------------------------------------------------------------------
| List of Members Shortened Links
|--------------------------------------------------------------------------
|
|
*/

namespace App\Filament\Pages\Member;

use App\Models\ShortLink;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Links extends Page implements HasTable
{
    use InteractsWithTable;

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

    public function table(Table $table): Table
    {
        return $table
            ->query(ShortLink::myShortLinks())
            ->columns([
                TextColumn::make('short_url')
                    ->label('Shortened URL')
                    ->getStateUsing(fn (ShortLink $shortLink) => formatFinalShortenedUrl($shortLink->short_url))
                    ->url(fn (ShortLink $shortLink) => formatFinalShortenedUrl($shortLink->short_url))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('original_url')
                    ->label('Original URL')
                    ->url(fn (ShortLink $shortLink) => $shortLink->original_url)
                    ->searchable()
                    ->sortable(),
            ]);
    }
}
