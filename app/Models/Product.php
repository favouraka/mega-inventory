<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
}
