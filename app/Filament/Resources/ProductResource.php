<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\ViewProduct;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Resources\ProductResource\RelationManagers\StocksRelationManager;
use App\Filament\Resources\UserResource\Pages\ViewUsers;
use App\Filament\Widgets\StatsOverview;
use App\Models\Product;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\ViewAction as ActionsViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\QueryBuilder;
use Filament\Tables\Filters\QueryBuilder\Constraints\TextConstraint;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    protected static  ?string $navigationGroup = 'Products';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Product information')
                        ->schema([
                            TextInput::make('title')->label('Name')->required(),
                            RichEditor::make('description')->required(),
                            FileUpload::make('images')->image()->disk('public')->multiple()->fetchFileInformation(false)->maxSize(1024)->maxFiles(5)->required(),
                        ]),
                //
                
                Section::make('Category')
                        ->columns(2)
                        ->schema([
                            Select::make('category_id')
                                ->relationship('category','name')
                                ->searchable()->required()
                                ->createOptionForm([
                                        TextInput::make('name'),
                                    ]),
                        ]),
                //

                Section::make('Shipping Information')
                        ->columns(2)
                        ->schema([
                            TextInput::make('weight')->label('Weight in grams')->type('number')->required(),
                            TextInput::make('width')->label('Width in cm')->type('number')->required(),
                            TextInput::make('length')->label('Length in cm')->type('number')->required(),
                            TextInput::make('height')->label('Height in cm')->type('number')->required(),
                        ]),
                // stock information section

                Section::make('Stock Information')
                        ->columns(2)
                        ->schema([
                            TextInput::make('upc_code')->label('UPC Code')->type('number')->required(),
                            TextInput::make('sku_code')->label('SKU Code')->required(),
                            TextInput::make('brand')->required(),
                            TextInput::make('model')->required(),
                            TextInput::make('size')->required(),
                            TextInput::make('color')->required(),
                            TextInput::make('batch')->required(),
                            TextInput::make('manufacturer')->required(),
                            TextInput::make('production_date')->label('Production Date')->type('date')->required(),
                            TextInput::make('expiry_date')->label('Expiry Date')->type('date')->required(),
                        ]),

                // pricing section
                Section::make('Pricing Information')
                        ->columns(2)
                        ->schema([
                            TextInput::make('price_cfa')->label('Price in CFA')->type('number')->requiredWithout('price_ngn'),
                            TextInput::make('price_ngn')->label('Price in NGN')->type('number')->requiredWithout('price_cfa'),
                        ]),
            ]);
    }

    public static function canCreate(): bool
    {
        return auth()->user()->is_admin;
    }
    

    public static function infolist(Infolist $infolist) : Infolist 
    {
        return $infolist->schema([
            ComponentsSection::make('Product Information')->schema([                
                TextEntry::make('title'),
                TextEntry::make('description'),
                ImageEntry::make('images')->square(),
            ]),
            ComponentsSection::make('Category')->schema([
                TextEntry::make('category.name'),
            ]),
            ComponentsSection::make('Shipping Information')->schema([
                TextEntry::make('weight')->label('Weight in grams'),
                TextEntry::make('width')->label('Width in cm'),
                TextEntry::make('length')->label('Length in cm'),
                TextEntry::make('height')->label('Height in cm'),
            ]),
            ComponentsSection::make('Stock Information')->schema([
                TextEntry::make('manufacturer')->label('Manufacturer'),
                TextEntry::make('model')->label('Model'),
                TextEntry::make('brand')->label('Brand'),
                TextEntry::make('production_date')->label('Production Date')->dateTime(),
                TextEntry::make('expiry_date')->label('Expiry Date')->dateTime(),
                RepeatableEntry::make('stocks')
                    ->columnSpan('full')
                    ->schema([
                        TextEntry::make('store.name'),
                        TextEntry::make('quantity'),
                    ]),
            ]),
            ComponentsSection::make('Pricing Information')->schema([
                TextEntry::make('price_cfa')->label('Price in CFA')->numeric()->money('NGN'),
                TextEntry::make('price_ngn')->label('Price in NGN')->numeric()->money('CFA'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                TextColumn::make('title')->searchable(),
                ImageColumn::make('images')->default('Not Available'),
                TextColumn::make('price_ngn')->label('Price (₦)'),
                TextColumn::make('price_cfa')->label('Price (₣)'),
                
                ])
                ->filters([
                // 
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                CreateAction::make('Add to inventory')
                    ->color('success')
                    ->label('Add to Inventory')
                    ->icon('heroicon-o-plus')
                    ->accessSelectedRecords()
                    ->hidden(function(array $data, Product $record){
                       return $record->stocks()->whereStoreId(auth()->user()->store->id)->count() ;
                    })
                    ->createAnother(false)
                    ->modalHeading('Add product to inventory')
                    ->successNotificationTitle('Saved successfully')
                    ->form([
                        TextInput::make('store')
                                ->default(auth()->user()->store->name)->disabled(),
                        TextInput::make('quantity')->numeric()->required(),
                    ])->action(function(array $data, Product $record) {
                            auth()->user()->store->stocks()->create([
                                'quantity' => $data['quantity'],
                                'product_id' => $record->id
                            ]);
                    }),
               Tables\Actions\EditAction::make()->visible(auth()->user()->is_admin),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()->visible(auth()->user()->is_admin),
                ])->visible(auth()->user()->is_admin)
            ]);
    }



    public static function getRelations(): array
    {
        return [
            //
            StocksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            // 'create' => Pages\CreateProduct::route('/create'),
            // 'edit' => Pages\EditProduct::route('/{record}/edit'),
            'view' => ViewProduct::route('/{record}/view'),
        ];
    }
}
