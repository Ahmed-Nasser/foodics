<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ingredients = Ingredient::all()->pluck('id')->toArray();
        foreach ($ingredients as $ingredient){
            Stock::factory()->create([
                'ingredient_id' => $ingredient,
                'ingredient_amount' => Arr::random([100, 150, 200, 50, 25, 10, 70, 60])
            ]);
        }
    }
}
