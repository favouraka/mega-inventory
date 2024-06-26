<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Pages\ViewProduct;
use App\Filament\Resources\ProductResource\RelationManagers\StocksRelationManager;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

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
                            FileUpload::make('images')->image()
                                        ->disk('public')->multiple()
                                        ->fetchFileInformation(false)
                                        // ->imageResizeMode('contain')
                                        // ->imageCropAspectRatio('1:1')
                                        // ->imageResizeTargetWidth('1024')
                                        // ->imageResizeTargetHeight('1024')
                                        ->maxSize(8192)
                                        ->maxFiles(5)
                                        ->required(),
                            TextInput::make('carton_qty')->label('Quantity in carton')->required(),
                        ]),
                //
                
                Section::make('Category')
                        ->columns(2)
                        ->schema([
                            Select::make('category_id')
                                ->relationship('category','name')
                                ->searchable()->required()
                                ->createOptionForm([
                                        TextInput::make('name')->label('Category name'),
                                    ]),
                        ]),
                //

                Section::make('Shipping Information')
                        ->columns(2)
                        ->schema([
                            TextInput::make('weight')->label('Weight in grams')->type('number'),
                            TextInput::make('width')->label('Width in cm')->type('number'),
                            TextInput::make('length')->label('Length in cm')->type('number'),
                            TextInput::make('height')->label('Height in cm')->type('number'),
                        ]),
                // stock information section

                Section::make('Stock Information')
                        ->columns(2)
                        ->schema([
                            TextInput::make('upc_code')->label('UPC Code')->type('number'),
                            TextInput::make('sku_code')->label('SKU Code'),
                            TextInput::make('brand'),
                            TextInput::make('model'),
                            TextInput::make('size'),
                            TextInput::make('color'),
                            TextInput::make('batch'),
                            TextInput::make('manufacturer'),
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
                TextEntry::make('description')->html(),
                ImageEntry::make('images')->square(),
                TextEntry::make('carton_qty')->label('Quantity in carton')->numeric(),
            ])->columns(2),
            ComponentsSection::make('Category')->schema([
                TextEntry::make('category.name'),
            ]),
            ComponentsSection::make('Shipping Information')->schema([
                TextEntry::make('weight')->label('Weight in grams'),
                TextEntry::make('width')->label('Width in cm'),
                TextEntry::make('length')->label('Length in cm'),
                TextEntry::make('height')->label('Height in cm'),
            ])->columns(2),
            ComponentsSection::make('Stock Information')->schema([
                TextEntry::make('manufacturer')->label('Manufacturer'),
                TextEntry::make('model')->label('Model'),
                TextEntry::make('brand')->label('Brand'),
                TextEntry::make('production_date')->label('Production Date')->dateTime(),
                TextEntry::make('expiry_date')->label('Expiry Date')->dateTime(),
            ])->columns(2),
            ComponentsSection::make('Pricing Information')->schema([
                TextEntry::make('price_ngn')->label('Price in NGN')->numeric()->money('NGN'),
                TextEntry::make('price_cfa')->label('Price in CFA')->numeric()->money('CFA'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Product::orderBy('title', 'asc'))
            ->columns([
                //
                TextColumn::make('title')->searchable(),
                TextColumn::make('carton_qty'),
                ImageColumn::make('images')->default('Not Available'),
                TextColumn::make('price_ngn')->label('Price (₦)'),
                TextColumn::make('price_cfa')->label('Price (₣)'),
                
                ])
                ->filters([
                // 
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('Add to inventory')
                    ->color('success')
                    ->label('Add to Inventory')
                    ->icon('heroicon-o-plus')
                    ->accessSelectedRecords()
                    ->hidden(fn(array $data, Product $record) =>
                       $record->inventories()->whereStoreId(auth()->user()->store->id)->count() 
                    )
                    // ->createAnother(false)
                    ->modalHeading('Add product to inventory')
                    ->successNotificationTitle('Saved successfully')
                    ->form([
                        TextInput::make('store')
                                ->default(auth()->user()->store->name)->disabled(),
                        TextInput::make('quantity')->numeric()->required(),
                        TextInput::make('qty_pieces')->numeric()->required(),
                    ])->action(fn(array $data, Product $record) => 
                            auth()->user()->store->inventories()->create([
                                'quantity' => $data['quantity'],
                                'product_id' => $record->id,
                                'qty_pieces' => $data['qty_pieces']
                            ])
                    ),
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
