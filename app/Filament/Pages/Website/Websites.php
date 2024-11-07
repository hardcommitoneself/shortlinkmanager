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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Notifications\Notification;

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
            ->description('Websites')
            ->query(Website::myWebsites())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('api_key')
                    ->badge()
                    ->copyable(),
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
                    ->action(function (array $data): void {
                        try {
                            $website = new Website($data);

                            $website->save();

                            Notification::make() 
                                ->title('Success')
                                ->success()
                                ->body('Added sucessfully')
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make() 
                                ->title('Unexpected error')
                                ->danger()
                                ->body($th->getCode() == 23000 ? 'Duplicated website' : $th->getMessage())
                                ->send();
                        }
                    })
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->required()
                            ->maxLength(255)
                    ]),
                Action::make('delete')
                    ->requiresConfirmation()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->action(fn (Website $record) => $record->delete())
            ]);
    }
}

