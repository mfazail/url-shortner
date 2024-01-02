<?php

namespace App\Filament\Profile\Pages;

use Filament\Pages\Dashboard as FilamentDashboard;
use App\Filament\Profile\Widgets\UrlMonthsOverview;
use App\Filament\Profile\Widgets\UrlRefererOverview;

class Dashboard extends FilamentDashboard
{
    public function getWidgets(): array
    {
        return [
            UrlMonthsOverview::class,
            UrlRefererOverview::class,
        ];
    }
}
