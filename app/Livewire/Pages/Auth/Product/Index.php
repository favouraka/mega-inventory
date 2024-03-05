<?php

namespace App\Livewire\Pages\Auth\Product;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;

use App\Models\Product;


#[Title('Products')]
class Index extends Component
{
    use WithPagination;

    public $page;
    public $search;

    public $filters = [
        'price_ngn' => '',
        'title' => '',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function getProductsProperty()
    {
        return Product::when(
                            $this->search, 
                            function($query, $search){
                                $query->where('title','like','%'.$search.'%')
                                    ->orWhere('upc_code','like','%'.$search.'%')
                                    ->orWhere('sku_code','like','%'.$search.'%');
                            }
                        )
                        // orders by product title 
                        ->when(
                            $this->filters['title'], 
                            function($query, $filter_title){
                                $query->orderBy('title', $filter_title);
                        })
                        // orders by naira price
                        ->when(
                            $this->filters['price_ngn'], 
                            function($query, $filter_price_ngn){
                                $query->orderBy('price_ngn', $filter_price_ngn);
                        })
                        ->latest()->paginate(20);
    }

    public function render()
    {
        return view('livewire.pages.auth.product.index')->layout('layouts.dashboard');
    }
}
