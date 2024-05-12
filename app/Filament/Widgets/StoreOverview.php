<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StoreOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Store name', auth()->user()->store->name),
            Stat::make('Total products in this location', auth()->user()->store->stocks->count()),
            Stat::make('Total stock avaialable', auth()->user()->store->stocks->sum('quantity')),
        ];
    }
}
