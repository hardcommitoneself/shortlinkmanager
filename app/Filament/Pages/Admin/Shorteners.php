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

use App\Models\Shortener;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\RawJs;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\HtmlString;

class Shorteners extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.admin.shorteners';

    protected static ?string $slug = 'admin/shorteners';

    public function table(Table $table): Table
    {
        return $table
            //->description('Websites')
            ->query(Shortener::query())
            ->striped()
            ->columns([
                TextColumn::make('name')
                    ->url(fn (Shortener $record) => $record->referral, true)
                    ->searchable()
                    ->sortable(),
                //TextColumn::make('api_link')
                //    ->searchable()
                //    ->sortable(),
                TextColumn::make('cpm')
                    ->label('CPM')
                    ->money('usd')
                    ->sortable(),
                TextColumn::make('views')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Added')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Updated')
                    ->sortable(),
            ])
            ->defaultSort(fn ($query) => $query->orderBy('status', 'desc')->orderBy('updated_at', 'asc'))
            ->headerActions([
                CreateAction::make()
                    ->label('Add Shortener')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Add Shortener')
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('api_link')
                            ->label('API Link')
                            ->required()
                            ->default('https://example.com/api?api={apikey}&url={url}')
                            ->maxLength(255),
                        TextInput::make('views')
                            ->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('cpm')
                            ->label('CPM')
                            ->prefixIcon('heroicon-o-currency-dollar')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->default(1),
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
            ])
            ->filters([
                SelectFilter::make('status')
                ->label('Shortener Status')
                ->options([
                    '1' => 'Enabled',
                    '0' => 'Disabled',
                ])
                ->default('1')
            ])
            ->actions([
                Action::make('activate')
                    ->label(fn (Shortener $record) => $record->status ? 'Disable '.$record->name :  'Enable '.$record->name)
                    ->iconButton()
                    ->color(fn (Shortener $record) => $record->status ? 'warning' : 'primary')
                    ->icon(fn (Shortener $record) => $record->status ? 'heroicon-o-signal-slash' : 'heroicon-o-signal')
                    ->requiresConfirmation()
                    ->action(function (Shortener $record) {
                        $record->status = !$record->status;

                        $record->save();

                        Notification::make()
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body(!$record->status ? $record->name.' has been disabled' : $record->name.' has been enabled')
                            ->send();
                    }),
                EditAction::make('edit')
                    ->iconButton()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->modalHeading(fn (Shortener $record) => new HtmlString('Edit <a href="'.str_replace('/ref/AvalonRychmon', '/payout-rates', $record->referral).'" target="_blank">'. $record->name.'</a>'))
                    //->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('name'),
                        TextInput::make('api_link')
                            ->label('API Key'),
                        TextInput::make('views')
                            //->numeric()
                            ->default(1)
                            ->minValue(1),
                        TextInput::make('cpm')
                            ->label('CPM')
                            ->prefixIcon('heroicon-o-currency-dollar')
                            ->mask(RawJs::make('$money($input)'))
                            ->stripCharacters(',')
                            ->numeric()
                            ->inputMode('decimal'),
                        TextInput::make('referral'),
                        TextInput::make('demo'),
                        TextArea::make('withdraw')
                    ])
                    ->successNotification(
                        Notification::make()
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body(fn (Shortener $record) => $record->name.' has been updated')
                     )
                    ->closeModalByClickingAway(false)
            ]);
    }
}
