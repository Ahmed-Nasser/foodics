<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredientNames = [
            'beef',
            'chicken',
            'red-sauce',
            'white-sauce',
            'cheese',
            'onion',
            'flour',
            'bacon'
        ];
        foreach ($ingredientNames as $ingredientName) {
            Ingredient::factory()->create(['name' => $ingredientName]);
        }

    }
}
