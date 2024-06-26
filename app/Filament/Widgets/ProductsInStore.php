<?php

namespace App\Filament\Widgets;

use App\Facades\Cart;
use App\Filament\Pages\NewOrder;
use App\Filament\Resources\ProductResource\Pages\ViewProduct;
use App\Models\Inventory;
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
use Filament\Notifications\Notification;


class ProductsInStore extends BaseWidget
{
    protected array|int|string $columnSpan = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Inventory::whereStoreId(auth()->user()->store->id)
            )
            ->columns([
                TextColumn::make('product.title')->searchable(),
                TextColumn::make('product.carton_qty')->label('Products in a carton')->numeric()->default(0),
                TextColumn::make('quantity')->label('Number of cartons in inventory')->numeric()->default(0),
                TextColumn::make('qty_pieces')->label('Quantity of pieces in inventory')->numeric()->default(0),
                TextColumn::make('total_sales')->numeric()->counts('sales')->default(fn($record) => $record->sales->count()),
                TextColumn::make('generated_sales')
                            // ->label('')
                            ->numeric()
                            ->default(function(Inventory $record){
                                return $record->sales->sum( fn($e) => (($e->sale_price) * $e->quantity) );
                            }),
            ])->actions([
                Action::make('view_product')
                    ->color('gray')
                    ->label('View')
                    ->icon('heroicon-o-eye')
                    ->accessSelectedRecords()
                    ->url(function(Inventory $record){
                        return ViewProduct::getUrl(['record' => $record->product->id]);
                    }),
                Action::make('edit_inventory')
                    ->color('warning')
                    ->label('Edit')
                    ->icon('heroicon-o-pencil-square')
                    ->accessSelectedRecords()
                    ->visible(auth()->user()->is_admin == 'administrator' || auth()->user()->is_admin == 'manager')
                    ->form([
                        TextInput::make('quantity')->default(fn($record) => ($record->quantity) )->numeric()->required(),
                        TextInput::make('qty_pieces')->default(fn($record) => ($record->qty_pieces) )->numeric()->required(),
                    ])->action(fn (array $data, Inventory $record) => $record->update($data)),
                Action::make('restock_product')
                    ->color('success')
                    ->label('Restock')
                    ->icon('heroicon-o-arrow-path')
                    ->accessSelectedRecords()
                    ->form([
                        TextInput::make('quantity')->numeric()->required(),
                        TextInput::make('supplier'),
                        TextInput::make('cost_price'),
                    ])->action(function(array $data, Inventory $record){
                            $record->update([
                                'quantity' => ($record->quantity + $data['quantity']),
                            ]);
                            $record->restocks()->create([...$data, 'user_id' => auth()->user()->id,]);
                            Notification::make()->title('Restocked Successfully!')->success()->send();
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
