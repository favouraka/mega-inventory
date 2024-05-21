<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\IconEntry\IconEntrySize;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use PHPUnit\Event\Code\Test;

class PrintReciept extends Page 
{
    public string $reference;
    public Order $order;

    protected $queryString = [
        'reference'
    ];

    public function mount()
    {
        $this->order = Order::whereReference($this->reference)->first();
    }
    

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.print-reciept';

    protected static bool  $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')->icon('heroicon-o-printer')->color(Color::Blue)->action(fn () => $this->dispatch('print-file')),
            Action::make('share')->icon('heroicon-o-share')->color(Color::Green)->action(fn () => $this->dispatch('share-file')),
            Action::make('download')->icon('heroicon-o-cloud-arrow-down')->color(Color::Fuchsia)->action(fn () => $this->dispatch('download-file')),
        ];  
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
                    ->record($this->order)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('reference')
                                ->color('success'),
                        TextEntry::make('total_paid')
                                ->columnSpanFull()
                                ->default(fn(Order $record) => $record->sales->sum(fn($sale) => $sale->sale_price * $sale->quantity ))
                                ->view('components.price-text'),
                        ComponentsSection::make('Store Information')
                                ->schema([
                                    TextEntry::make('store.name'),
                                    TextEntry::make('store.address')->label('Address'),
                                ]),
                        ComponentsSection::make('Sales Information')
                                ->schema([
                                    RepeatableEntry::make('sales')
                                        ->columns(2)
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('product_details')->default(fn(Sale $record) => $record->inventory->product->title . ' x ' . $record->quantity),
                                            TextEntry::make('amount')->default(fn(Sale $record) => '₦'.$record->sale_price * $record->quantity),
                                        ])
                                ])
                    ]);
    }
}
