<?php

namespace App\Filament\Pages\Member;

use App\Models\Shortener;
use App\Models\ShortenerSetting;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class Shorteners extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static string $view = 'filament.pages.member.shorteners';

    protected static ?string $slug = 'member/shorteners';

    protected static ?int $navigationSort = 2;

    public function mount()
    {
        abort_if(! Auth::user()->can('view shorteners'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view shorteners');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Shortener::properShorteners())
            ->columns([
                TextColumn::make('name')
                    //->icon('heroicon-o-signal')
                    ->color(fn (Shortener $record): string => match ($record->isSettingExisted()) {
                        true => $record->setting()->status ? 'primary' : 'warning',
                        false => 'gray'
                    })
                    ->url(fn (Shortener $record) => $record->referral, true)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cpm')
                    ->label('CPM')
                    ->money('usd')
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('views')
                    ->getStateUsing(function (Shortener $record) {
                        return $record->setting()->views ?? $record->views;
                    })
                    ->alignCenter()
                    ->sortable(),
                TextColumn::make('tags')
                    ->badge()
                    ->separator(','),
                TextColumn::make('api_key')
                    ->label('API Key')
                    ->badge()
                    ->copyable(fn (Shortener $record) => $record->isSettingExisted())
                    ->color(fn (Shortener $record) => $record->isSettingExisted() ? 'gray' : 'warning')
                    ->getStateUsing(function (Shortener $record) {
                        return $record->setting()->api_key ?? 'Add API Key to Enable';
                    })
                    ->icon(fn (Shortener $record) => $record->isSettingExisted() ? null : 'heroicon-o-cog-6-tooth')
                    ->limit(21)
                    ->extraHeaderAttributes(['class' => 'w-8'])
                    ->action(
                        Action::make('Active')
                            ->modalHeading('Add API Key')
                            ->modalDescription(function (Shortener $record) {
                                return new HtmlString('Get your API Key from <b><a href="'.$record->referral.'" target="_blank">'.$record->name.'</a></b>\'s Developers API page.');
                            })
                            ->modalWidth(MaxWidth::Large)
                            ->form([
                                TextInput::make('api_key')
                                    ->label('API Key')
                                    ->required()
                                    ->maxLength(255),
                            ])
                            ->action(function (Shortener $record, array $data) {
                                $setting = new ShortenerSetting;

                                $setting->api_key = $data['api_key'];
                                $setting->priority = Auth::user()->numberOfSettings() + 1;
                                $setting->user_id = Auth::user()->id;
                                $setting->shortener_id = $record->id;

                                $setting->save();

                                Notification::make()
                                    ->title('Success')
                                    ->icon('heroicon-o-check-circle')
                                    ->success()
                                    ->body($record->name.' updated successfully')
                                    ->send();
                            })
                            ->closeModalByClickingAway(false)
                            ->visible(fn (Shortener $record) => ! $record->isSettingExisted())
                    ),
            ])
            ->striped()
            ->defaultSort('settings_count', 'desc')
            ->filters([
                SelectFilter::make('tags')
                    ->multiple()
                    ->options([
                        'active' => 'Active',
                        'scam' => 'Scam',
                        'closed' => 'Closed',
                    ])
                    ->query(function (Builder $query, $state): Builder {
                        if (empty($state['values'])) {
                            return $query;
                        }

                        return $query->whereJsonContains('tags', $state['values']);
                    }),
            ])
            ->actions([
                Action::make('Activate/Deactivate')
                    ->label(fn (Shortener $record) => $record->setting()->status ? 'Disable '.$record->name : 'Enable '.$record->name)
                    ->iconButton()
                    ->color(fn (Shortener $record) => $record->setting()->status ? 'warning' : 'primary')
                    ->icon(fn (Shortener $record) => $record->setting()->status ? 'heroicon-o-signal-slash' : 'heroicon-o-signal')
                    ->requiresConfirmation()
                    ->action(function (Shortener $record) {
                        $setting = $record->setting();

                        $setting->status = ! $setting->status;

                        $setting->save();

                        Notification::make()
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body(! $record->setting()->status ? $record->name.' has been disabled' : $record->name.' has been enabled')
                            ->send();
                    })
                    ->visible(fn (Shortener $record) => $record->isSettingExisted()),
                Action::make('Edit')
                    ->modalHeading('Edit API Key')
                    ->modalDescription(function (Shortener $record) {
                        return new HtmlString('Get your API Key from <b><a href="'.$record->referral.'" target="_blank">'.$record->name.'</a></b>\'s Developers API page.');
                    })
                    ->modalWidth(MaxWidth::Large)
                    ->label(function (Shortener $record) {
                        return 'Edit '.$record->name;
                    })
                    ->iconButton()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->fillForm(fn (Shortener $record) => $record->setting()->only(['api_key', 'views']))
                    ->form([
                        TextInput::make('api_key')
                            ->label('API Key')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Shortener $record, array $data) {
                        $setting = $record->setting();

                        $setting->api_key = $data['api_key'];

                        $setting->save();

                        Notification::make()
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body('Update this setting successfully')
                            ->send();
                    })
                    ->visible(fn (Shortener $record) => $record->isSettingExisted()),
            ]);
    }
}
