<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Support\Colors\Color;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UsersInStore extends BaseWidget
{
    protected array|int|string $columnSpan = 2;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(
                User::where('store_id', auth()->user()->store->id)
            )
            ->columns([
                // ...
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('username')->badge()->color(Color::Amber),
            ]);
    }
}
