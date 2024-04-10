<?php

namespace App\Livewire\Pages\Auth\Stock;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\WithPagination;

#[Title('Inventory Stock')]
class Index extends Component
{
    use WithPagination;
    
    // stock property
    public $search;
    public $page = 1;

    // queryString property
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1]
    ];

    public function getStocksProperty()
    {
        // Your logic to retrieve the stock value goes here
        // For example, you can fetch the stock from a database or an API
        $stocks = auth()->user()->store->stocks()->when($this->search, function($query, $search){
                        // Filter the stock based on the product name
                        $query->whereHas('product', function($query) use ($search){
                            $query->where('name', 'like', '%'.$search.'%');
                        });
                    })->latest()->paginate(20);

        return $stocks;
    }
    
    public function render()
    {
        return view('livewire.pages.auth.stock.index')->layout('layouts.dashboard');
    }
}
