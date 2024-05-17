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
            Stat::make('Total products in this location', auth()->user()->store->inventories->count()),
            Stat::make('Total stock avaialable', auth()->user()->store->inventories->sum('quantity')),
        ];
    }
}
