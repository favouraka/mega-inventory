<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description',
        'category_id', 
        'weight', 'height', 'length', 'width',
        'upc_code', 'sku_code',
        'price_ngn', 'price_cfa',
        'color', 'size', 'batch', 'manufacturer', 'brand', 'production_date', 'expiry_date',
        'images'
    ];

    protected $casts = [
        'images' => 'array',
    ];
    

    protected function priceNgn():  Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value/100),
            set:  fn ($value) => ($value*100)
        );
    }

    protected function priceCfa():  Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value/100),
            set:  fn ($value) => ($value*100)
        );
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventories() : HasMany {
        return $this->hasMany(Inventory::class);
    }
}
