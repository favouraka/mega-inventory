<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Facades\Cart;
use App\Filament\Pages\NewOrder;
use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderInfoWidget;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('continue_order')
                    ->icon('heroicon-o-shopping-bag')
                    ->label('Continue Order')
                    ->color(Color::Amber)
                    ->visible(Cart::content()->count()),
            Actions\Action::make('create_order')
                            ->label('New Order')
                            ->requiresConfirmation()
                            ->action(function() {
                                Cart::clear();
                                return redirect(NewOrder::getUrl());
                            })
                            ->icon('heroicon-o-shopping-cart'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderInfoWidget::class,
        ];
    }
}
