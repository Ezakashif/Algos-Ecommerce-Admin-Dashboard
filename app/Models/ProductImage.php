<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductVariant;
class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['product_variant_id', 'path', 'is_primary', 'sort_order'];

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

     public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
