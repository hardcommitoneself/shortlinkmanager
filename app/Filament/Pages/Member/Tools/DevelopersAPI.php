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

namespace App\Filament\Pages\Member\Tools;

use App\Models\Website;
use App\Actions\SectionHeaderSelectAction;
use CodeWithDennis\SimpleAlert\Components\Forms\SimpleAlert;
use Filament\Actions\SelectAction;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\HtmlString;
use Livewire\Attributes\On;

class DevelopersAPI extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = '';

    protected static string $view = 'filament.pages.member.tools.developers-api';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?string $title = 'Developers API';

    protected static ?string $navigationLabel = 'Developers API';

    //protected ?string $subheading = 'Custom Page Subheading';

    protected static ?string $slug = 'member/tools/api';

    protected static ?int $navigationSort = 3;

    public ?array $developerAPIFormData = [];

    public ?int $currentWebsiteId = 1;

    public function mount(): void
    {
        abort_if(! Auth::user()->can('view developers-api'), 403);

        try {
            $website = Website::myWebsites()->orderBy('id')->firstOrFail();

            $this->currentWebsiteId = $website->id;

            $this->developerAPIFormData = [
                'name' => $website->name,
                'api_key' => $website->api_key,
                'request_link' => config('app.url').'/shorten?api='.$website->api_key.'&url=yourdestinationlink.com&alias=CustomAlias',
                'json_response' => '{"status":"success","shortenedUrl":""https:\/\/get4links.com\/xxxxx""}',
                'request_link_for_result_as_text' => config('app.url').'/api?api='.$website->api_key.'&url=yourdestinationlink.com&alias=CustomAlias&format=text',
            ];
        } catch (\Throwable $th) {
            $this->currentWebsiteId = null;

            $this->developerAPIFormData = [
                'name' => 'Undefined',
                'api_key' => 'Undefined',
                'request_link' => config('app.url').'/shorten?api='.'undefined'.'&url=yourdestinationlink.com&alias=CustomAlias',
                'json_response' => '{"status":"success","shortenedUrl":""https:\/\/get4links.com\/xxxxx""}',
                'request_link_for_result_as_text' => config('app.url').'/api?api='.'undefined'.'&url=yourdestinationlink.com&alias=CustomAlias&format=text',
            ];
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return Auth::user()->can('view developers-api');
    }

    public function getForms(): array
    {
        return [
            'developersAPIForm',
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            // SelectAction::make('currentWebsiteId')
            //     ->label('Select Website')
            //     ->options(Website::myWebsites()->pluck('name', 'id')->toArray())
        ];
    }

    public function updatedCurrentWebsiteId()
    {
        $website = Website::find($this->currentWebsiteId);

        $this->developerAPIFormData = [
            'name' => $website->name,
            'api_key' => $website->api_key,
            'request_link' => config('app.url').'/shorten?api='.$website->api_key.'&url=yourdestinationlink.com&alias=CustomAlias',
            'json_response' => '{"status":"success","shortenedUrl":""https:\/\/get4links.com\/xxxxx""}',
            'request_link_for_result_as_text' => config('app.url').'/api?api='.$website->api_key.'&url=yourdestinationlink.com&alias=CustomAlias&format=text',
        ];
    }

    public function developersAPIForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Developers API - ' . Website::find($this->currentWebsiteId)?->name ?? null)
                    ->headerActions([
                        SectionHeaderSelectAction::make('currentWebsiteId')
                            ->label('Select Website')
                            ->options(Website::myWebsites()->pluck('name', 'id')->toArray())
                        ])
                    ->reactive()
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
                        SimpleAlert::make('test-danger-alert')
                            ->info()
                            ->description(fn () => new HtmlString(
                                '
                                <p style="margin-left: 0.5rem">
                                    <b>NOTE</b> api & url are required fields and the other fields like alias, format & type are optional.
                                </p>
                                '
                            ))
                    ]),
                Section::make('Usage of API')
                    ->schema([
                        Tabs::make('Usage of API')
                            ->tabs([
                                Tab::make('PHP')
                                    ->schema([
                                        SimpleAlert::make('test-danger-alert')
                                            ->info()
                                            ->description(fn () => new HtmlString(
                                                '
                                                <p style="margin-left: 0.5rem">
                                                    To use the API in your PHP application, you need to send a GET request via file_get_contents or cURL. Please check the below sample examples using file_get_contents Using JSON Response
                                                </p>
                                                '
                                            )),
                                        SimpleAlert::make('test-danger-alert')
                                            ->description(fn () => new HtmlString(
                                                '
                                                    <code>
                                                        $long_url = urlencode("yourdestinationlink.com"); <br><br>

                                                        $api_token = "'.$this->developerAPIFormData['api_key'].'"; <br><br>

                                                        $api_url = "'.config('app.url').'/api?api={$api_token}&url={$long_url}&alias=CustomAlias"; <br><br>

                                                        $result = @json_decode(file_get_contents($api_url),TRUE); <br><br>

                                                        if($result["status"] === "error") { <br><br>

                                                            echo $result["message"]; <br><br>

                                                        } else { <br><br>

                                                            echo $result["shortenedUrl"]; <br><br>

                                                        }
                                                    </code>
                                                '
                                            )),
                                        SimpleAlert::make('test-danger-alert')
                                            ->info()
                                            ->description(fn () => new HtmlString(
                                                '
                                                <p style="margin-left: 0.5rem">
                                                    Using Plain Text Response
                                                </p>
                                                '
                                            )),
                                        SimpleAlert::make('test-danger-alert')
                                            ->description(fn () => new HtmlString(
                                                '
                                                    <code>
                                                        $long_url = urlencode("yourdestinationlink.com"); <br><br>

                                                        $api_token = "'.$this->developerAPIFormData['api_key'].'"; <br><br>

                                                        $api_url = "'.config('app.url').'/api?api={$api_token}&url={$long_url}&alias=CustomAlias&format=text"; <br><br>

                                                        $result = @file_get_contents($api_url); <br><br>

                                                        if( $result ){ <br><br>

                                                            echo $result; <br><br>

                                                        }
                                                    </code>
                                                '
                                            )),
                                    ]),
                                Tab::make('Curl'),
                                Tab::make('JavaScript'),
                                Tab::make('Embeded'),
                            ]),
                    ]),
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
