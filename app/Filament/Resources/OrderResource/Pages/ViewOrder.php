<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Colors\Color;
use App\Filament\Pages\PrintReciept;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            // stats for order widgets
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Print Reciept')
                ->icon('heroicon-o-printer')
                ->visible(fn(Order $record) => $record->status === 'completed')
                ->color(Color::Purple)
                ->url(fn(Order $record) => PrintReciept::getUrl().'?reference='.$record->reference),
            Actions\Action::make('Print Invoice')
                ->icon('heroicon-o-printer')
                ->color('primary')
                // ->url(fn(Order $record) => route('show-invoice', ['order' => $record->id])),
        ];
    }
}
