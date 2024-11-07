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

namespace App\Filament\Pages\Website;

use App\Models\Website;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;

class Websites extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $view = 'filament.pages.website.websites';

    protected static ?string $navigationGroup = 'Websites';

    protected static ?int $navigationSort = 1;

    public function table(Table $table): Table
    {
        return $table
            ->query(Website::query())
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('url'),
                TextColumn::make('api_key'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->required()
                            ->maxLength(255)
                    ])
            ])
            ->actions([
                Action::make('delete')
                    ->requiresConfirmation()
                    ->action(fn (Website $record) => $record->delete())    
            ]);
    }
}

