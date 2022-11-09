<?php

namespace Database\Factories;

use App\Models\Ingredient;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StockFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'ingredient_id' => fn () => Ingredient::factory()->create()->id,
            'ingredient_amount' => $this->faker->numerify(),
        ];
    }
}
