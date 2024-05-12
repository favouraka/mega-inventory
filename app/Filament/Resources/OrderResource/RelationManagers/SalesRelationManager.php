<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Models\Sale;
use App\Models\Stock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesRelationManager extends RelationManager
{
    protected static string $relationship = 'sales';

    

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('stock.product.title')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('stock.product.title')
            ->columns([
                Tables\Columns\TextColumn::make('stock.product.title'),
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('sale_price'),
                Tables\Columns\TextColumn::make('total')
                                        ->default(function(Sale $record){
                                            return $record->quantity * $record->sale_price;
                                        }),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\CreateAction::make(),
                Action::make('show_sales'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(auth()->user()->is_admin),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
