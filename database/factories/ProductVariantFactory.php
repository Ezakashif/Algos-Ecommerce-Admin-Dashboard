<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Product;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => Product::factory(), // creates a product automatically
            'sku' => strtoupper($this->faker->bothify('SKU-#####')),  // e.g. SKU-12345
            'attributes' => json_encode([
                'color' => $this->faker->safeColorName(),
                'size' => $this->faker->randomElement(['S', 'M', 'L', 'XL']),
            ]),
            'price_override' => $this->faker->randomFloat(2, 10, 500),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
