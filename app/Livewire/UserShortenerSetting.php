<?php

namespace App\Livewire;

use App\Models\ShortenerSetting;
use App\Models\Website;
use App\Models\WebsiteShortenerSetting;
use Livewire\Component;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class UserShortenerSetting extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public $website_id;

    public function table(Table $table): Table
    {
        return $table
            ->query(ShortenerSetting::properShortenerSettings($this->website_id))
            ->columns([
                TextColumn::make("name")
                    ->label('Shortener')
                    ->getStateUsing(fn (ShortenerSetting $record) => $record->shortener->name)
                    ->color(fn (ShortenerSetting $record) => match(
                        WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                            ->where('website_id', $this->website_id)
                            ->exists()
                    ) {
                        true => match (
                            WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                                ->where('website_id', $this->website_id)
                                ->first()
                                ->status
                        ) {
                            true => 'primary',
                            false => 'gray'
                        },
                        false => 'gray'
                    })
            ])
            ->defaultSort('priority')
            ->actions([
                Action::make('Activate/Deactivate')
                    ->iconButton()
                    ->icon(fn (ShortenerSetting $record) => match(
                        WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                            ->where('website_id', $this->website_id)
                            ->exists()
                    ) {
                        true => match(
                            WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                                ->where('website_id', $this->website_id)
                                ->first()
                                ->status
                        ) {
                            true => 'heroicon-o-signal-slash',
                            false => 'heroicon-o-signal'
                        },
                        false => 'heroicon-o-signal'
                    })
                    ->color(fn (ShortenerSetting $record) => match(
                        WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                            ->where('website_id', $this->website_id)
                            ->exists()
                    ) {
                        true => match(
                            WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                                ->where('website_id', $this->website_id)
                                ->first()
                                ->status
                        ) {
                            true => 'warning',
                            false => 'primary'
                        },
                        false => 'primary'
                    })
                    ->action(function (ShortenerSetting $record) {
                        if(WebsiteShortenerSetting::where('shortener_settings_id', $record->id)->where('website_id', $this->website_id)->exists()) {
                            $websiteShortenerSetting = WebsiteShortenerSetting::where('shortener_settings_id', $record->id)->where('website_id', $this->website_id)->first();

                            if($websiteShortenerSetting->status) {
                                $websiteShortenerSettingsUnderThisOne = WebsiteShortenerSetting::where('website_id', $this->website_id)
                                    ->where('priority', '>', $websiteShortenerSetting->priority)
                                    ->where('priority', '<', 999999)
                                    ->orderBy('priority', 'desc')
                                    ->get();
                                
                                foreach ($websiteShortenerSettingsUnderThisOne as $setting) {
                                    $setting->priority = $setting->priority - 1;

                                    $setting->save();
                                }

                                $websiteShortenerSetting->priority = 999999;
                            } else {
                                $websiteShortenerSetting->priority = WebsiteShortenerSetting::where('website_id', $this->website_id)
                                    ->where('priority', '<', 999999)
                                    ->orderBy('priority', 'desc')
                                    ->first()
                                    ->priority + 1;
                            }

                            $websiteShortenerSetting->status = !$websiteShortenerSetting->status;

                            $websiteShortenerSetting->save();
                        } else {
                            $websiteShortenerSetting = new WebsiteShortenerSetting;

                            $websiteShortenerSetting->priority = WebsiteShortenerSetting::where('website_id', $this->website_id)->count() + 1;
                            $websiteShortenerSetting->shortener_settings_id = $record->id;
                            $websiteShortenerSetting->website_id = $this->website_id;

                            $websiteShortenerSetting->save();
                        }
                    }),
                Action::make('upPriority')
                    ->iconButton()
                    ->icon('heroicon-o-chevron-up')
                    ->visible(fn (ShortenerSetting $record) => $record->status && (ShortenerSetting::properShortenerSettings($this->website_id)->where('website_shortener_settings.priority', '<', '999998')->orderBy('priority')->first() ? $record->id !== ShortenerSetting::properShortenerSettings($this->website_id)->where('website_shortener_settings.priority', '<', '999998')->orderBy('priority')->first()->id : false))
                    ->color(fn (ShortenerSetting $record) => match ($record->status) {
                        1 => 'primary',
                        0 => 'gray'
                    })
                    ->disabled(fn (ShortenerSetting $record) => !$record->status)
                    ->action(function (ShortenerSetting $record) {
                        $websiteShortenerSetting = WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                            ->where('website_id', $this->website_id)
                            ->first();
                        $beforeWebsiteShortenerSetting = WebsiteShortenerSetting::where('website_id', $this->website_id)
                            ->where('priority', '=', $websiteShortenerSetting->priority - 1)
                            ->first();

                        $websiteShortenerSetting->priority = $websiteShortenerSetting->priority - 1;
                        $beforeWebsiteShortenerSetting->priority = $beforeWebsiteShortenerSetting->priority + 1;

                        $websiteShortenerSetting->save();
                        $beforeWebsiteShortenerSetting->save();
                    }),
                Action::make('downPriority')
                    ->iconButton()
                    ->icon('heroicon-o-chevron-down')
                    ->visible(fn (ShortenerSetting $record) => $record->status && (ShortenerSetting::properShortenerSettings($this->website_id)->where('website_shortener_settings.status', true)->orderBy('priority', 'desc')->first() ? $record->id !== ShortenerSetting::properShortenerSettings($this->website_id)->where('website_shortener_settings.status', true)->orderBy('priority', 'desc')->first()->id : false))
                    ->color(fn (ShortenerSetting $record) => match ($record->status) {
                        1 => 'primary',
                        0 => 'gray'
                    })
                    ->disabled(fn (ShortenerSetting $record) => !$record->status)
                    ->action(function (ShortenerSetting $record) {
                        $websiteShortenerSetting = WebsiteShortenerSetting::where('shortener_settings_id', $record->id)
                            ->where('website_id', $this->website_id)
                            ->first();
                        $afterWebsiteShortenerSetting = WebsiteShortenerSetting::where('website_id', $this->website_id)
                            ->where('priority', '=', $websiteShortenerSetting->priority + 1)
                            ->first();

                        $websiteShortenerSetting->priority = $websiteShortenerSetting->priority + 1;
                        $afterWebsiteShortenerSetting->priority = $afterWebsiteShortenerSetting->priority - 1;

                        $websiteShortenerSetting->save();
                        $afterWebsiteShortenerSetting->save();
                    }),
            ]);
    }

    public function render()
    {
        return view('livewire.user-shortener-setting');
    }
}
