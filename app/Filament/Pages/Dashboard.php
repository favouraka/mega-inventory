<?php

namespace App\Filament\Pages;

use App\Filament\Resources\OrderResource\Widgets\OrderInfoWidget;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\StoreOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            StoreOverview::class,
            OrderInfoWidget::class,
        ];
    }
}
