<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'sku', 'attributes','stock_quantity', 'base_price','has_variant'];

     protected $casts = [
        'attributes' => 'array',
    ];
    
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function images()
{
    return $this->hasMany(ProductImage::class);
}

public function primaryImage()
{
    return $this->hasOne(ProductImage::class)
                ->where('is_primary', true)
                ->whereNull('product_variant_id');
}


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

}
