<?php

namespace App\Livewire\Includes;

use App\Models\Order;
use App\Models\Sale;
use Livewire\Component;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SalesTable extends Component implements HasTable, HasForms
{
    use InteractsWithForms, InteractsWithTable;

    public Order $order;

    public function mount($order)
    {
        $this->order = $order;
    }

    public function table(Table $table): Table
    {
        return $table
                ->query(Sale::whereHas('order', fn($query) => $query->whereReference($this->order->reference)))
                ->paginated(false)
                ->columns([
                    TextColumn::make('inventory.product.title'),
                    TextColumn::make('quantity'),
                    TextColumn::make('sale_price'),
                ]);
    }
    
    public function render()
    {
        return view('livewire.includes.sales-table');
    }
}
