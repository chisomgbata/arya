<?php

namespace App\Filament\App\Pages;

use App\Filament\App\Widgets\Calendar;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class Appointments extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::Calendar;
    protected string $view = 'filament.app.pages.appointments';

    public function getHeaderWidgets(): array
    {
        return [
            Calendar::class
        ];
    }
}
