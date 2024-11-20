<?php
 
namespace App\Filament\Pages\Member;

use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Actions\Concerns\HasForm;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Forms\Components\RichEditor;
use Livewire\Livewire;
use App\Filament\Widgets\SampleChart;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.member.dashboard';

    protected static ?string $slug = 'member/dashboard';

    public ?array $data = [];

    public function mount(): void
    {
        abort_if(!Auth::user()->can('view dashboard'), 403);

        $this->form->fill();
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view dashboard');
    }

    public function getHeaderWidgets(): array
    {
        return [
            SampleChart::class,
            SampleChart::class,
        ];
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Announcements')
                    ->description('Announcements are coming here')
                    ->schema([
                        SimpleAlert::make('test-danger-alert')
                            ->danger()
                            ->title('Hoorraayy! Your request has been approved! 🎉')
                            ->description('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.'),
                        SimpleAlert::make('test-info-alert')
                            ->info()
                            ->title('Hoorraayy! Your request has been approved! 🎉')
                            ->description('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.')
                    ]),
                Section::make('Latest updates')
                    ->description('Latest updates')
            ])
            ->statePath('data');    
    }
}