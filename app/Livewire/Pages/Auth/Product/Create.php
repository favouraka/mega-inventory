<?php

namespace App\Livewire\Pages\Auth\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\Product;
use App\Models\ProductImage;

#[Title('Create New Product')]
class Create extends Component
{
    use WithFileUploads;
    // basic information
    public $title;
    public $description;
    // images
    public $images = [];
    // category
    public $category_id;
    // delivery info
    public $weight;
    public $width;
    public $length;
    public $height;
    // inventory info
    public $sku_code;
    public $upc_code;
    // prices
    public $price_ngn;
    public $price_cfa;

    // uploaded image
    public $upload_image;

    public function save()
    {
        
        $validated = $this->validate([
                        'images' => 'array|required|bail',
                        'title' => 'required|min:8|bail',
                        'description' => 'required|min:10',
                        'category_id' => 'required|numeric',
                        'weight' => 'required|numeric',
                        'height' => 'required|numeric',
                        'width' => 'required|numeric',
                        'length' => 'required|numeric',
                        'sku_code' => 'string|nullable|unique:products',
                        'upc_code' => 'required|string',
                        'price_ngn' => 'required|numeric',
                        'price_cfa' => 'required|numeric',
                    ]);
                    

        $product = Product::create([...$validated]);

        $product_images = [];

        foreach($this->images as $key => $value){
            $file_name = \Str::slug($product->title.$key.now()).'.'.$value->getClientOriginalExtension();
            $file_path = $value->storeAs('photos', $file_name, 'public');
            $product_images[] = [
                'alt' => $validated['title'].'-image-'.$key,
                'path' => $file_path,
            ];
        }
        // save product images
        $product->images()->createMany($product_images);
        
        $this->reset();
        session()->flash('success');

    }

    public function removeImage($key)
    {
        $this->images = collect($this->images)->forget($key);
    }

    public function uploadImage()
    {
        $validated = $this->validate([
            'upload_image' => 'required|image|max:2048',
        ]);

        $this->images = collect($this->images)->push($validated['upload_image']);

        $this->dispatch('photo-uploaded');
        $this->reset([
            'upload_image'
        ]);
    }

    public function render()
    {
        return view('livewire.pages.auth.product.create')->layout('layouts.dashboard');
    }
}
