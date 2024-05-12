<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Settings';
    protected static ?int $navigationSort = 1;


    // Filament 3.x supported with new fresh api
    public static function canCreate(): bool
    {
        return  auth()->user()->is_admin && User::count() <= 5;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                TextInput::make('name')->required(),
                TextInput::make('email')->required(),
                TextInput::make('username')->required(),
                Select::make('store_id')->required()->label('Store')->relationship('store', 'name'),
                TextInput::make('password')
                ->password()
                ->requiredWith('password_confirmation')
                // ->bail()
                ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                ->dehydrated(fn ($state) => filled($state)),
                TextInput::make('password_confirmation')->confirmed('password')->password()->default('password'),
            ]);
    }
    

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('store.name')->searchable(),
                TextColumn::make('username')->badge()->searchable(),                
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(!auth()->user()->is_admin),
                ViewAction::make()->icon('heroicon-o-eye'),
                Action::make('change_password')
                    ->hidden(!auth()->user()->is_admin)
                    ->label('Change password')->icon('heroicon-o-key')->color('danger')->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            // 'create' => Pages\CreateUser::route('/create'),
            // 'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
