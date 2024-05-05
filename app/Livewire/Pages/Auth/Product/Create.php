<?php

namespace App\Livewire\Pages\Auth\Product;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Title;
use App\Models\Product;
use Illuminate\Support\Str;

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
    // manufacturer metadata
    public $manufacturer;
    public $production_date;
    public $expiry_date;
    public $batch;
    public $model;

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
                        // shipping info is nullable
                        'weight' => 'nullable|numeric',
                        'height' => 'nullable|numeric',
                        'width' => 'nullable|numeric',
                        'length' => 'nullable|numeric',
                        // inventory info
                        'sku_code' => 'string|nullable|unique:products',
                        'upc_code' => 'required|string',
                        'price_ngn' => 'required_without:price_cfa|nullable|numeric',
                        'price_cfa' => 'required_without:price_ngn|nullable|numeric',
                        // product metadata
                        'manufacturer' => 'required|string',
                        'production_date' => 'required|date',
                        'expiry_date' => 'required_with:production_date|after:production_date|date',
                        'batch' => 'nullable',
                        'model' => 'nullable|string',
                        'color' => 'nullable|string',
                        'size' => 'nullable|string',
                        'brand' => 'nullable|string',
                    ]);
                    

        $product = Product::create([...$validated]);

        $product_images = [];

        foreach($this->images as $key => $value){
            $file_name = Str::slug($product->title.$key.now()).'.'.$value->getClientOriginalExtension();
            $file_path = $value->storeAs('photos', $file_name, 'public');
            $product_images[] = [
                'alt' => $validated['title'].'-image-'.$key,
                'path' => $file_path,
            ];
        }
        // save product images
        $product->images()->createMany($product_images);
        
        session()->flash('success', 'Product created sucessfully');
        return redirect()->route('dashboard.product.index');
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
        // add a variable for $images as the $this property
        $images = $this->images;
        return view('livewire.pages.auth.product.create')->layout('layouts.dashboard');
    }
}
