<?php

/*
|--------------------------------------------------------------------------
| Full Page Script
|--------------------------------------------------------------------------
|
| A JS Script that converts all URLs on page to shortened links
| Form Screenshot https://prnt.sc/BRAvaVo3TIyg
| https://get4links.com/member/tools/full

<script type="text/javascript">
    var app_url = 'https://get4links.com/';
    var app_api_token = 'd43ff55a92125a01bbc666016db3ce76b9630e36';
    var app_advert = 2;
    var app_domains = ["gr8.cc"];
</script>
<script src='//get4links.com/js/full-page-script.js'></script>

|
*/

namespace App\Filament\Pages\Member\Tools;

use Filament\Pages\Page;

class FullPageScript extends Page
{

    //protected static ?string $navigationIcon = 'heroicon-o-globe-alt';

    protected static string $view = 'filament.pages.member.tools.full-page-script';

    protected static ?string $slug = 'member/tools/full';

    protected static ?string $navigationGroup = 'Tools';

    protected static ?int $navigationSort = 2;

}