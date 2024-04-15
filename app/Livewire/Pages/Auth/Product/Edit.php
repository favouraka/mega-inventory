<?php

namespace App\Livewire\Pages\Auth\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\Product;
use Illuminate\Support\Str;

#[Title('Edit Product')]
class Edit extends Component
{
    use WithFileUploads;
    // model
    public $product;
    // basic information
    public $title;
    public $description;
    // category
    public $category_id;
    // manufacturer metadata
    public $manufacturer;
    public $production_date;
    public $expiry_date;
    public $batch;
    public $model;

    public $images = [];
    // stock metadata
    public $color;
    public $size;
    public $brand;

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
                        // shipping information is nullable
                        'weight' => 'nullable|numeric',
                        'height' => 'nullable|numeric',
                        'width' => 'nullable|numeric',
                        'length' => 'nullable|numeric',
                        // inventory info
                        'sku_code' => 'string|nullable|unique:products,id,'.$this->product->id,
                        'upc_code' => 'required',
                        'price_ngn' => 'required|numeric',
                        'price_cfa' => 'required|numeric',
                        // 
                        'manufacturer' => 'required|string',
                        'production_date' => 'required|date',
                        'expiry_date' => 'required_with:production_date|gt:production_date|date',
                        'batch' => 'nullable',
                        'model' => 'nullable|string',
                        'color' => 'nullable|string',
                        'size' => 'nullable|string',
                        'brand' => 'nullable|string',
                    ]);

        
                    

        $this->product->update([...$validated]);
        session()->flash('success', 'Product edited successfully');
        return redirect()->route('dashboard.product.view', ['product' => $this->product->id]);

    }

    public function generateSku()
    {
        $brand = $this->brand ? substr($this->brand, 0, 3) : 'GEN';
        $model = $this->model ? substr($this->model, 0, 3) : 'NIL';
        $color = $this->color ? substr($this->color, 0, 3) : 'NIL';
        $randomCode = rand(100, 999);

        $skuCode = $brand . $model . $color . $randomCode;

        $this->sku_code = $skuCode;
    }

    public function removeImage($key)
    {
        // remove image from images array
        $image = $this->images[$key];
        $this->product->images()->findOrFail($image->id)->delete();
        // 
        $this->images = collect($this->images)->forget($key);
    }

    public function uploadImage()
    {
        $validated = $this->validate([
            'upload_image' => 'required|image|max:2048',
        ]);
        // save upload_image to $this->product images relationship and update public image property with $thi->product->images
        $this->product->images()->create([
            'path' => $validated['upload_image']->store('products', 'public'),
            'alt' => 'product image',
        ]);

        $this->images = Product::findorFail($this->product->id)->images;

        $this->dispatch('photo-uploaded');
        $this->reset([
            'upload_image'
        ]);
    }

    public function mount(Product $product)
    {
        $this->product = $product;
        $this->title = $product->title;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->manufacturer = $product->manufacturer;
        $this->production_date = $product->production_date;
        $this->expiry_date = $product->expiry_date;
        $this->batch = $product->batch;
        $this->model = $product->model;
        $this->color = $product->color;
        $this->size = $product->size;
        $this->brand = $product->brand;
        $this->weight = $product->weight;
        $this->width = $product->width;
        $this->length = $product->length;
        $this->height = $product->height;
        $this->sku_code = $product->sku_code;
        $this->upc_code = $product->upc_code;
        $this->price_ngn = $product->price_ngn;
        $this->price_cfa = $product->price_cfa;

        $this->images = $product->images;
    }

    public function render()
    {
        return view('livewire.pages.auth.product.edit')->layout('layouts.dashboard');
    }
}
