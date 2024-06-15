<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected function salePrice():  Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value/100),
            set:  fn ($value) => ($value*100)
        );
    }

    protected function stockPrice():  Attribute
    {
        return Attribute::make(
            get: fn($value) => ($value/100),
            set:  fn ($value) => ($value*100)
        );
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function restocks()
    {
        return $this->hasMany(Restock::class);
    }
    
}