<?php

namespace App\Filament\Widgets;

use App\Facades\Cart;
use App\Filament\Pages\NewOrder;
use App\Filament\Resources\ProductResource\Pages\ViewProduct;
use App\Models\Stock;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ProductsInStore extends BaseWidget
{
    protected array|int|string $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Stock::whereStoreId(auth()->user()->store->id)
            )
            ->columns([
                TextColumn::make('product.title')->searchable(),
                TextColumn::make('quantity')->numeric()->default(0),
                TextColumn::make('total_sales')->numeric()->counts('sales')->default(fn($record) => $record->sales->count()),
                TextColumn::make('sale.quantity')
                            ->label('Quantity Sold')
                            ->numeric()
                            ->default(function(Stock $record){
                                return $record->sales()->sum('quantity');
                            }),
            ])->actions([
                Action::make('view_product')
                        ->color('gray')
                        ->label('View')
                        ->icon('heroicon-o-eye')
                        ->accessSelectedRecords()
                        ->url(function(Stock $record){
                            return ViewProduct::getUrl(['record' => $record->product->id]);
                        }),
                ActionGroup::make([
                    Action::make('view_in_order')
                        ->label('View Order')
                        ->color(Color::Amber)
                        ->accessSelectedRecords()
                        ->visible(fn($record) => Cart::exists($record->id))
                        ->icon('heroicon-o-arrow-top-right-on-square')
                        ->url(NewOrder::getUrl()),
                    Action::make('add_to_order')
                        ->accessSelectedRecords()
                        ->hidden(fn($record) =>  Cart::exists($record->id))->icon('heroicon-o-plus')
                        ->color(Color::Green)
                        ->action(function($record){
                            Cart::add($record->id);
                        })->successNotificationTitle('Added successfully'),
                    Action::make('remove_from_order')
                        ->accessSelectedRecords()
                        ->visible(fn($record) =>  Cart::exists($record->id))->icon('heroicon-o-minus')
                        ->color(Color::Red)
                        ->action(function($record){
                            Cart::remove($record->id);
                        })->successNotificationTitle('Removed successfully')
                ])->label('More')->link()
                
            ]);
    }
}
