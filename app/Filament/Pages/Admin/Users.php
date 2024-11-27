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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
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
                TextColumn::make('roles')
                    ->getStateUsing(fn (User $record) => $record->getRoleNames())
                    ->badge()
                    ->separator(','),
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
                Action::make('edit')
                    ->iconButton()
                    ->icon('heroicon-o-cog-6-tooth')
                    ->label(fn (User $record) => new HtmlString('Edit '.$record->name))
                    ->modalHeading(fn (User $record) => new HtmlString('Edit '.$record->name))
                    ->modalWidth(MaxWidth::Large)
                    ->fillForm(fn (User $record) => array_merge($record->toArray(), [
                        'roles' => $record->roles->pluck('name')
                    ]))
                    ->form([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->placeholder('yourname@email.com')
                            ->maxLength(255),
                        Select::make('roles')
                            ->multiple()
                            ->options([
                                'Admin' => 'Admin',
                                'Moderator' => 'Moderator',
                                'User' => 'User'
                            ])
                    ])
                    ->action(function (User $record, array $data) {
                        $record->update([
                            'name' => $data['name'],
                            'email' => $data['email']
                        ]);

                        $updatedRoles = $data['roles'];

                        $record->syncRoles($updatedRoles);

                        Notification::make()
                            ->title('Success')
                            ->icon('heroicon-o-check-circle')
                            ->success()
                            ->body($record->name.' has been updated')
                            ->send();
                    })
                    ->closeModalByClickingAway(false),
            ]);
    }
}
