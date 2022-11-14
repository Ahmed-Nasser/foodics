<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Ingredient;
use Illuminate\Support\Arr;
use App\Models\IngredientProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<IngredientProduct>
 */
class IngredientProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
           'product_id'        => fn () => Product::factory()->create()->id,
           'ingredient_id'     => fn () => Ingredient::factory()->create()->id,
           'ingredient_amount' => Arr::random([250, 150, 100, 30, 25, 20]),
        ];
    }
}
