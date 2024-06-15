<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            // Stat::make('Store name', auth()->user()->store->name),
            Stat::make('Available stock in store', auth()->user()->store->inventories->sum('quantity')),
            Stat::make('Available products', Product::count()),
            Stat::make('Total sales this week', Sale::where('created_at','>=', Carbon::now()->subWeek())->count()),
        ];
    }
}
