<?php

/*
|--------------------------------------------------------------------------
| Developers API
|--------------------------------------------------------------------------
|
| This page shows how to use cURL to create the shortened links
| https://get4links.com/member/tools/api
|
*/

namespace App\Filament\Pages\Tools;

use App\Models\Website;
use Filament\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Actions\Action;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class DevelopersAPI extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = '';

    protected static string $view = 'filament.pages.tools.developers-api';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?string $title = 'Developers API';

    protected static ?string $navigationLabel = 'Developers API';

    //protected ?string $subheading = 'Custom Page Subheading';

    protected static ?string $slug = 'member/tools/api';

    protected static ?int $navigationSort = 3;

    public ?array $developerAPIFormData = [];

    public function mount(): void
    {
        $website = Website::myWebsites()->orderBy('id')->first();

        $this->developerAPIFormData = [
            'id' => $website->id,
            'name' => $website->name,
            'api_key' => $website->api_key,
            'request_link' => config('app.url') . '/api?api=' . $website->api_key . '&url=yourdestinationlink.com&alias=CustomAlias',
            'json_response' => '{"status":"success","shortenedUrl":""https:\/\/get4links.com\/xxxxx""}',
            'request_link_for_result_as_text' => config('app.url') . '/api?api=' . $website->api_key . '&url=yourdestinationlink.com&alias=CustomAlias&format=text',
        ];
    }

    public function getForms(): array
    {
        return [
            'developersAPIForm'
        ];
    }

    public function developersAPIForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make($this->developerAPIFormData['name'])
                    ->headerActions([
                        Action::make('Select Website')
                            ->form([
                                Select::make('website')
                                    ->options(Website::myWebsites()->pluck('name', 'id')->toArray())
                                    ->default($this->developerAPIFormData['id'])
                                    ->searchable()
                            ])
                            ->action(function (array $data) {
                                $selectedWebsiteId = $data['website'];

                                $website = Website::find($selectedWebsiteId);

                                $this->developerAPIFormData = [
                                    'id' => $website->id,
                                    'name' => $website->name,
                                    'api_key' => $website->api_key,
                                    'request_link' => config('app.url') . '/api?api=' . $website->api_key . '&url=yourdestinationlink.com&alias=CustomAlias',
                                    'json_response' => '{"status":"success","shortenedUrl":""https:\/\/get4links.com\/xxxxx""}',
                                    'request_link_for_result_as_text' => config('app.url') . '/api?api=' . $website->api_key . '&url=yourdestinationlink.com&alias=CustomAlias&format=text',
                                ];
                            })
                    ])
                    ->schema([
                        TextInput::make('api_key')
                            ->label('Your API Token')
                            ->disabled()
                            ->suffixAction(
                        Action::make('copy')
                                    ->iconButton()
                                    ->icon('heroicon-o-clipboard')
                                    ->action(fn () => $this->dispatch('copy-to-clipboard', ['text' => $this->developerAPIFormData['api_key']]))
                            ),
                        SimpleAlert::make('test-danger-alert')
                            ->description(fn () => new HtmlString(
                                '
                                <p class="">
                                    For developers SLM prepared <b>API</b> which returns responses in <b>JSON</b> or <b>TEXT</b> formats. <br> 
                                    Currently there is one method which can be used to shorten links on behalf of your account. <br> 
                                    All you have to do is to send a <b>GET</b> request with your API token and URL Like the following:
                                </p>
                                '
                            )),
                        TextInput::make('request_link')
                            ->label('')
                            ->disabled()
                            ->suffixAction(
                        Action::make('copy')
                                    ->iconButton()
                                    ->icon('heroicon-o-clipboard')
                                    ->action(fn () => $this->dispatch('copy-to-clipboard', ['text' => $this->developerAPIFormData['request_link']]))
                            ),
                        SimpleAlert::make('test-danger-alert')
                            ->description(fn () => new HtmlString(
                                '
                                <p class="">
                                    You will get a JSON response like the following
                                </p>
                                '
                            )),
                        TextInput::make('json_response')
                            ->label('')
                            ->disabled()
                            ->suffixAction(
                        Action::make('copy')
                                    ->iconButton()
                                    ->icon('heroicon-o-clipboard')
                                    ->action(fn () => $this->dispatch('copy-to-clipboard', ['text' => $this->developerAPIFormData['json_response']]))
                            ),
                        SimpleAlert::make('test-danger-alert')
                            ->description(fn () => new HtmlString(
                                '
                                <p class="">
                                    If you want a TEXT response just add <b>&format=text</b> at the end of your request as the below example. This will return just the short link. Note that if an error occurs, it will not output anything.
                                </p>
                                '
                            )),
                        TextInput::make('request_link_for_result_as_text')
                            ->label('')
                            ->disabled()
                            ->suffixAction(
                        Action::make('copy')
                                    ->iconButton()
                                    ->icon('heroicon-o-clipboard')
                                    ->action(fn () => $this->dispatch('copy-to-clipboard', ['text' => $this->developerAPIFormData['request_link_for_result_as_text']]))
                            ),
                    ])
            ])
            ->statePath('developerAPIFormData');
    }

    #[On('copy-to-clipboard')]
    public function copyToClipboard(): void
    {
        Notification::make() 
            ->title('Success')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->body('Copied')
            ->send();
    }
}