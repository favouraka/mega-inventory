<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class OrderInfoWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            //
            Stat::make('Orders made this week', Order::where('store_id', auth()->user()->store_id)
                        ->where('created_at', '>=', Carbon::now()->subWeek())->count()),
        ];
    }
}
