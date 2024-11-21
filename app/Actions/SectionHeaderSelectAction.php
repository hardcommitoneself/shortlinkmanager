<?php

namespace App\Actions;

use Filament\Actions\Concerns\HasSelect;
use Filament\Forms\Components\Actions\Action;

class SectionHeaderSelectAction extends Action
{
    use HasSelect;

    protected function setUp(): void
    {
        parent::setUp();

        $this->view('filament-actions::select-action');
    }
}
