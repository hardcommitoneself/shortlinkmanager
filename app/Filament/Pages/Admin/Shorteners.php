<?php

/*
|--------------------------------------------------------------------------
| List of URL Shorteners
|--------------------------------------------------------------------------
|
| Name, CPM, API Key, Priority, Views, Enable/Disable
|
*/

namespace App\Filament\Pages\Admin;

use Filament\Pages\Page;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Models\Shortener;
use Filament\Tables\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\ToggleColumn;

class Shorteners extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static string $view = 'filament.pages.admin.shorteners';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->description('Websites')
            ->query(Shortener::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('api_link')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cpm')
                    ->prefix('$')
                    ->sortable(),
                TextColumn::make('views')
                    ->sortable(),
                ToggleColumn::make('status'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add Shortener')
                    ->modalHeading('Add Shortener')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('api_link')
                            ->required()
                            ->default('https://example.com/api?api={apikey}&url={url}')
                            ->maxLength(255),
                        TextInput::make('views')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('cpm')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('referral'),
                        TextInput::make('demo'),
                        TextInput::make('withdraw'),
                        Toggle::make('status')
                            ->inline(false)
                            ->default(true)
                    ])
                    ->action(function (array $data): void {
                        try {
                            $website = new Shortener($data);

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
            ]);
    }
}
