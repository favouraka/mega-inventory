<?php

namespace App\Livewire\Pages\Auth\Stock;

use App\Models\Product;
use Livewire\Component;
use Livewire\Attributes\Title;

#[Title('Add Stock')]
class Add extends Component
{
    public $product;
    public $store;

    public $quantity;

    public function getExistingProductProperty()
    {
        return $this->store->stocks()->whereHas('product', function($query){
                    $query->where('id', $this->product->id);
                })->first();
    }


    public function addToStore()
    {
        $validated = $this->validate([
                        'quantity' => 'required|numeric',
                    ]);

        // ensure stock does not exist in store

        if ($this->existingProduct) {
            $this->addError('product', 'Product already exists in store.');
        } else {
            // store stock in store
            $this->store->stocks()->create([
                'product_id' => $this->product->id,
                'quantity' => $validated['quantity'],
            ]);
        }

        // return to stock route with session success flash message
        session()->flash('success', 'Stock added successfully.');
        return redirect()->route('dashboard.stock.index');

    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->store = auth()->user()->store;
    }

    public function render()
    {
        return view('livewire.pages.auth.stock.add')->layout('layouts.dashboard');
    }
}
