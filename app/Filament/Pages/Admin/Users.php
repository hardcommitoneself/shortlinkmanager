<?php

/*
|--------------------------------------------------------------------------
| List of Users
|--------------------------------------------------------------------------
|
|
*/

namespace App\Filament\Pages\Admin;

use App\Models\User;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;

class Users extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Admin';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.admin.users';

    protected static ?string $slug = 'admin/users';

    public function mount()
    {
        abort_if(! Auth::user()->can('view users'), 403);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view users');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(User::query())
            ->striped()
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Add User')
                    ->icon('heroicon-o-plus')
                    ->modalHeading('Add User')
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->placeholder('yourname@email.com')
                            ->maxLength(255),
                        TextInput::make('password')
                            ->required()
                            ->password()
                            ->revealable()
                            ->maxLength(255),
                    ])
                    ->action(function (array $data): void {
                        try {
                            $user = new User($data);

                            $user->save();

                            Notification::make()
                                ->title('Success')
                                ->success()
                                ->body('Added sucessfully')
                                ->send();

                        } catch (\Throwable $th) {
                            Notification::make()
                                ->title('Unexpected error')
                                ->danger()
                                ->body($th->getCode() == 23000 ? 'Duplicated User' : $th->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make('edit')
                    ->iconButton()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->label(fn (User $record) => new HtmlString('Edit '.$record->name))
                    ->modalHeading(fn (User $record) => new HtmlString('Edit '.$record->name))
                    ->modalWidth(MaxWidth::Large)
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->placeholder('yourname@email.com')
                            ->maxLength(255),
                    ])
                    ->successNotification(
                        Notification::make()
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body(fn (User $record) => $record->name.' has been updated')
                    )
                    ->closeModalByClickingAway(false),
            ]);
    }
}
