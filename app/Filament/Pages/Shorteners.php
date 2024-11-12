<?php

namespace App\Filament\Pages;

use App\Models\ShortenerSetting;
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
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\IconColumn;

class Shorteners extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';

    protected static string $view = 'filament.pages.shorteners';

    protected static ?string $slug = 'member/shorteners';

    protected static ?int $navigationSort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->description('Websites')
            ->query(Shortener::activeShorteners())
            ->columns([
                IconColumn::make('status')
                    ->icon('heroicon-o-signal')
                    ->color(fn (Shortener $record): string => match($record->isSettingExisted()) {
                        true => $record->setting()->status ? 'primary' : 'warning',
                        false => 'gray'
                    }),
                TextColumn::make('name')
                    ->url(fn (Shortener $record) => $record->referral)
                    ->openUrlInNewTab()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cpm')
                    ->label('CPM')
                    ->money('usd')
                    ->sortable(),
                TextColumn::make('api_key')
                    ->label('API Key')
                    ->badge()
                    ->copyable(fn (Shortener $record) => $record->isSettingExisted())
                    ->color(fn (Shortener $record) => $record->isSettingExisted() ? 'primary' : 'warning')
                    ->getStateUsing(function (Shortener $record) {
                        return $record->setting()->api_key ?? 'Please active this shortener';
                    }),
                TextColumn::make('views')
                    ->getStateUsing(function (Shortener $record) {
                        return $record->setting()->views ?? $record->views;
                    })
                    ->sortable(),
            ])
            ->actions([
                Action::make('Active')
                    ->label('Active this shortener')
                    ->iconButton()
                    ->icon('heroicon-o-rocket-launch')
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('api_key')
                            ->label('API Key')
                            ->placeholder('***********')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('views')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                    ])
                    ->action(function (Shortener $record, array $data) {
                        $setting = new ShortenerSetting;

                        $setting->api_key = $data['api_key'];
                        $setting->views = $data['views'];
                        $setting->priority = Auth::user()->numberOfSettings() + 1;
                        $setting->user_id = Auth::user()->id;
                        $setting->shortener_id = $record->id;

                        $setting->save();

                        Notification::make() 
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body('Activate shortener successfully')
                            ->send();
                    })
                    ->visible(fn (Shortener $record) => !$record->isSettingExisted()),
                Action::make('Activate/Deactivate')
                    ->label(fn (Shortener $record) => $record->setting()->status ? 'Deactivate this shortener' : 'Activate this shortener')
                    ->iconButton()
                    ->color(fn (Shortener $record) => $record->setting()->status ? 'warning' : 'primary')
                    ->icon(fn (Shortener $record) => $record->setting()->status ? 'heroicon-o-signal-slash' : 'heroicon-o-signal')
                    ->requiresConfirmation()
                    ->action(function (Shortener $record) {
                        $setting = $record->setting();

                        $setting->status = !$setting->status;

                        $setting->save();

                        Notification::make() 
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body(!$record->setting()->status ? 'Deactivate shortener successfully' : 'Activate shortener successfully')
                            ->send();
                    })
                    ->visible(fn (Shortener $record) => $record->isSettingExisted()),
                Action::make('Edit')
                    ->label('Edit this setting')
                    ->iconButton()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->modalWidth(MaxWidth::Large)
                    ->fillForm(fn (Shortener $record) => $record->setting()->only(['api_key', 'views']))
                    ->form([
                        TextInput::make('api_key')
                            ->label('API Key')
                            ->placeholder('***********')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('views')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                    ])
                    ->action(function (Shortener $record, array $data) {
                        $setting = $record->setting();

                        $setting->api_key = $data['api_key'];
                        $setting->views = $data['views'];

                        $setting->save();

                        Notification::make() 
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body('Update this setting successfully')
                            ->send();
                    })
                    ->visible(fn (Shortener $record) => $record->isSettingExisted())
            ]);
    }
}
