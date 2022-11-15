<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Stock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

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
            $amount = Arr::random([300, 250, 200, 150, 100, 50]);
            Stock::factory()->create([
                'ingredient_id' => $ingredient,
                'ingredient_amount' => $amount,
                'initial_ingredient_amount' => $amount,
            ]);
        }
    }
}
