<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;
use App\Models\IngredientProduct;
use Illuminate\Support\Arr;

class IngredientProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $products = Product::all()->pluck('id')->toArray();
        $ingredients = Ingredient::all()->pluck('id')->toArray();
        foreach ($products as $productId){
            for ($i = 0; $i < 3; $i++){
                IngredientProduct::factory()->create([
                    'product_id'    => $productId,
                    'ingredient_id' => $ingredients[Arr::random([0,1,2])],
                ]);
            }
        }
    }
}
