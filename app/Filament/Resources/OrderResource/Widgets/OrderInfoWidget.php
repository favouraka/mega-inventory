<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrderInfoWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Orders made this week', auth()->user()->orders()->where('created_at', '>=', Carbon::now()->subWeek())->count()),
        ];
    }
}
