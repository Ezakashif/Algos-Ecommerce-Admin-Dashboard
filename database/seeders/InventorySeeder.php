<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Inventory;
use App\Models\CategoryProduct;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
       public function run(): void
    {
        // Create 5 products
        Product::factory()->count(5)->create()->each(function ($product) {
            // Create 3 variants per product using factory
            ProductVariant::factory()->count(3)->create([
                'product_id' => $product->id,
            ])->each(function ($variant) {
                // Create inventory for each variant
                Inventory::create([
                    'product_variant_id' => $variant->id,
                    'stock_quantity' => rand(5, 20),
                ]);
            });
        });
            CategoryProduct::factory()->count(30)->create(); // 30 random links
    }
    }
