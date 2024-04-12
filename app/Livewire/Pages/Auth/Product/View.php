<?php

namespace App\Livewire\Pages\Auth\Product;

use Livewire\Component;
use App\Models\Product;
use Livewire\Attributes\Title;


#[Title('Product View')]
class View extends Component
{
    public $product;

    public $number = 1;

    public function mount(Product $product)
    {
        $this->product = $product;
    }

    public function getStockDataProperty()
    {
        return $this->product->stocks()->with('store')->get();
    }

    public function assetUrl($path)
    {
        return asset('storage/'.$path);
    }

    public function render()
    {
        return view('livewire.pages.auth.product.view')->layout('layouts.dashboard');
    }
}
