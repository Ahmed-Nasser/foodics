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
        $amount = $this->faker->numerify();

        return [
            'id' => Str::uuid()->toString(),
            'ingredient_id' => fn () => Ingredient::factory()->create()->id,
            'ingredient_amount' => $amount,
            'initial_ingredient_amount' => $amount,
        ];
    }
}
