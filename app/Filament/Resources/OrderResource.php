<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\Pages\ViewOrder;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Filament\Resources\OrderResource\RelationManagers\SalesRelationManager;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Grid::make([
                'lg' => 2,
                'sm' => 1,
            ])->schema([
                    Section::make('Store Information')   
                            ->schema([
                                TextEntry::make('store.name')->label('Store Name'),
                                TextEntry::make('store.phone')->label('Phone'),
                                TextEntry::make('store.address')->label('Address'),
                            ]),
                    Section::make('Customer Information')   
                    ->schema([
                        TextEntry::make('customer_name'),
                        TextEntry::make('customer_phone'),
                        TextEntry::make('customer_email'),
                    ])            
                ])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('customer_name')->searchable(),
                TextColumn::make('customer_email')->searchable(),
                TextColumn::make('customer_phone')->searchable(),
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('status')->badge()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                EditAction::make()->visible(auth()->user()->is_admin)
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
            SalesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'view' => ViewOrder::route('view/{record}'),
            // 'create' => Pages\CreateOrder::route('/create'),
            // 'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
