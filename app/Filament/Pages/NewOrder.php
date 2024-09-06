<?php

namespace App\Filament\Pages;

use App\Facades\Cart;
use Filament\Actions\Action as ActionsAction;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Page;

class NewOrder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Checkout';

    protected static string $view = 'filament.pages.new-order';

    protected function getHeaderActions(): array
    {
        return [
            ActionsAction::make('Cancel Order')
                        ->outlined()
                        ->requiresConfirmation()
                        ->action( function(){
                            Cart::clear();
                            return redirect(Dashboard::getUrl());
                        })
                        ->color('danger'),
        ];
    }

}
