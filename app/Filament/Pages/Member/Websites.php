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

/*
TODO:  Add Error message for duplicate URL on Website Edit
*/

namespace App\Filament\Pages\Member;

use App\Models\Website;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class Websites extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-window';

    protected static string $view = 'filament.pages.member.websites';

    protected static ?string $slug = 'member/websites';

    protected static ?int $navigationSort = 1;

    public function mount()
    {
        abort_if(! Auth::user()->can('view websites'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view websites');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Website::myWebsites())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('URL')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('api_key')
                    ->label('API Key')
                    ->badge()
                    ->copyable()
                    ->copyMessage('API Key copied'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add a website')
                    ->icon('heroicon-o-plus')
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('URL')
                            ->url()
                            ->required()
                            ->maxLength(255)
                            ->placeholder('https://google.com'),
                    ])
                    ->action(function (array $data): void {
                        try {
                            $website = new Website($data);

                            $website->save();

                            Notification::make()
                                ->title('New website added')
                                ->icon('heroicon-o-check-circle')
                                ->success()
                                ->body($website->name.' added successfully')
                                ->send();
                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Unexpected Error')
                                ->icon('heroicon-o-exclamation-circle')
                                ->danger()
                                ->body($th->getCode() == 23000 ? 'Unable to add, duplicate website.' : $th->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Action::make('setting')
                    ->iconButton()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->modalWidth(MaxWidth::Large)
                    ->form(function (Website $record) {
                        return [
                            View::make('filament.components.user-shortener-settings')
                                ->viewData([
                                    'website_id' => $record->id,
                                ]),
                        ];
                    })
                    ->modalSubmitAction(false),
                EditAction::make()
                    ->iconButton()
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('url')
                            ->label('URL')
                            ->readOnly(),
                    ])
                    ->successNotification(function (array $data) {
                        Notification::make()
                            ->title('Website updated')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body($data['name'].' updated successfully')
                            ->send();
                    }),
                DeleteAction::make('delete')
                    ->modalHeading(function (Website $record) {
                        return 'Delete '.$record->name;
                    })
                    ->iconButton()
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(function (Website $record) {
                        $record->delete();
                        Notification::make()
                            ->title('Website deleted')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body($record->name.' deleted successfully')
                            ->send();
                    }),
            ]);
    }
}
