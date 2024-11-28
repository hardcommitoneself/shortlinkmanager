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
use App\Tables\Columns\ShortLinkColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
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
                ShortLinkColumn::make('short_url')
                    ->label('Shortened URL')
                    ->getStateUsing(fn (ShortLink $shortLink) => formatFinalShortenedUrl($shortLink->short_url))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at'),
            ])
            ->actions([
                EditAction::make('Edit')
                    ->iconButton()
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('original_url')
                            ->required()
                            ->maxLength(255),
                    ]),
                DeleteAction::make('Delete')
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->requiresConfirmation()
                    ->successNotification(function () {
                        Notification::make()
                            ->title('Shortened url deleted')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body('Shortened url has been deleted successfully')
                            ->send();
                    }),
            ]);
    }
}
