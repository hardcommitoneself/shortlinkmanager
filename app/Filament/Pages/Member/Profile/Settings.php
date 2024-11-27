<?php

namespace App\Filament\Pages\Member\Profile;

use App\Models\User;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Page;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Settings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static bool $shouldRegisterNavigation = false;

    protected static string $view = 'filament.pages.member.profile.settings';

    protected static ?string $slug = 'member/profile/settings';

    public function mount()
    {

    }

    protected function getForms(): array
    {
        return [
            'changeEmailForm',
            'changePasswordForm',
            'deleteForm',
        ];
    }

    public function collapseSection(): string
    {
        return $this->Section::collapsed(0);
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
                            ->placeholder('Enter your password'),
                    ])
                    ->footerActions([
                        Action::make('Change Email')
                            ->action(function (User $user) {
                                dd($user);
                            }),
                        Action::make('Cance')
                            ->action(function (User $user) {
                                dd($user);
                            }),
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
                    ])
                    ->schema([
                        TextInput::make('Current password')
                            ->password()
                            ->required()
                            ->currentPassword(),
                            //->helperText(new HtmlString('<a href="/password-reset/request">Forgot your password?</a>'))
                            //->hint(new HtmlString('<a href="/password-reset/request">Forgotten your password?</a>')),
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
                    ->footerActions([
                        Action::make('Change Password')
                            ->action(function (User $user) {
                                dd($user);
                            }),
                        Action::make('Cancel')
                            ->color('gray')
                            ->action(function (Set $set) { $set('section_open', 1); }),
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
                            ->color('danger'),
                    ])
                    ->schema([])
                    //->form([])
                    ->footerActions([ ])
                    //->collapsed(),
            ]);
    }
}
