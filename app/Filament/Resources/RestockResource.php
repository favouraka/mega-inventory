<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RestockResource\Pages;
use App\Filament\Resources\RestockResource\RelationManagers;
use App\Models\Restock;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RestockResource extends Resource
{
    protected static ?string $model = Restock::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';
    protected static ?string $navigationGroup = 'Inventory';
    protected static ?int $navigationSort = 3;

    // Filament 3.x supported with new fresh api
    public static function canCreate(): bool
    {
        return  false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                // TextInput::make('supplier')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Restock::whereHas('inventory' , function (Builder $query) {
                $query->where('store_id', auth()->user()->store->id);
            })->latest())
            ->columns([
                //
                TextColumn::make('inventory.product.title')->searchable(),
                TextColumn::make('quantity'),
                TextColumn::make('cost_price'),
                TextColumn::make('supplier'),
                TextColumn::make('user.name')->searchable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->hidden(true),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRestocks::route('/'),
            // 'create' => Pages\CreateRestock::route('/create'),
            // 'edit' => Pages\EditRestock::route('/{record}/edit'),
        ];
    }
}
