<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProductvARIANT;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
             'name' => $this->faker->unique()->words(3, true), // e.g., "Ultra Slim Phone"
            'description' => $this->faker->paragraph,
            'base_price' => $this->faker->randomFloat(2, 10, 500), // random price between $10 and $500
            'stock_quantity' => $this->faker->randomFloat(2, 10, 500), // random quantity between 10 and 500
            'has_variant' => $this->faker->boolean, // random boolean value
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
