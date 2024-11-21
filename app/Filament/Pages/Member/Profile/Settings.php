<?php

namespace App\Filament\Pages\Member\Profile;

use App\Models\User;
use Closure;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Actions\EditAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Filament\Support\Enums\MaxWidth;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Password;
use Illuminate\Contracts\Auth\Authenticatable;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.member.profile.settings';

    protected static ?string $slug = 'member/profile/settings';

    public function mount()
    {
        // $someModel = Email::find($state);
        // $set('section_open', $someModel?->default_option);
    }

    protected function getForms(): array
    {
        return [
            'changeEmailForm',
            'changePasswordForm',
            'deleteForm'
        ];
    }

    protected function getSaveFormAction(): Action
    {
        return parent::getSaveFormAction()
            ->label('Update User')
            ->icon('heroicon-o-check-circle')
            ->iconPosition(IconPosition::Before)
            ->color('success');
    }

    // Customize the "Cancel" button
    protected function getCancelFormAction(): Action
    {
        return parent::getCancelFormAction()
            ->label('Go Back')
            ->icon('heroicon-o-arrow-left')
            ->color('gray');
    }

    protected function getFormActions(): array
    {
        return [
            Actions\Action::make('Update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    protected function getCreateFormAction(): Action
    {
    return parent::getCreateFormAction()
        ->submit(form: null) // set the form data to null, prevent save process
        ->requiresConfirmation()
        ->action(function() {
            $this->closeActionModal(); // Close btn
            $this->create();  // process the create method
        });
    }

    public function changeEmailForm(Form $form): Form
    {
        return $form
            ->model(Auth::user())
            ->schema([
                Section::make('Email')
                    ->description(auth()->user()->email)
                    ->headerActions([
                        Action::make('test')
                        ->icon('')
                        ->button()
                        ->action(function (Set $set) {

                            $this->refreshFormData([
                                'section_open',
                            ]);
                            $set('section_open', 0);})
                            //->action(function (Set $set) { $set('section_open', 1); }),
                            //->hidden(fn (Get $get): bool => $get('section_open') != 0),
                        ])
                    ->schema([
                        TextInput::make('email')
                            ->label('New email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->placeholder('Enter your email address')
                            ->disableAutocomplete(),
                        TextInput::make('Current password')
                            ->label('Verify your password')
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->placeholder('Enter your password')
                    ])
                    ->footerActions([
                        Action::make('Save')
                            ->action(function (User $user) {
                                dd($user);
                            }),
                        Action::make('Cance')
                            ->action(function (User $user) {
                                dd($user);
                            })
                    ])
                    ->collapsed(fn (Get $get): bool => $get('section_open') != 1)
                    ->collapsible(),
            ]);
    }

    public function changePasswordForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Password')
                    ->description('Update your password and secure your account.')
                    ->headerActions([
                        Action::make('edit')
                            ->label('Change Password')
                            ->icon('')
                            ->form([
                                TextInput::make('Current password')
                                    ->password()
                                    ->required()
                                    ->currentPassword(),
                                TextInput::make('password')
                                    ->password()
                                    ->required()
                                    ->rule(Password::default())
                                    ->autocomplete('new-password')
                                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                                    ->live(debounce: 500)
                                    ->same('passwordConfirmation'),
                                TextInput::make('passwordConfirmation')
                                    ->password()
                                    ->required()
                                    ->dehydrated(false),
                            ])
                            ->modalWidth(MaxWidth::Large)

                    ])
                    ->collapsed(),
            ]);
    }

    public function deleteForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Delete Account')
                    ->description('This account will no longer be available, and all your saved data will be permanently deleted.')
                    ->headerActions([
                        Action::make('delete')
                            ->button()
                            ->label('Delete Account')
                            ->icon('')
                            ->color('danger')
                    ])
                    ->collapsed(),
            ]);
    }
}
