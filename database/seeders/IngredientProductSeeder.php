<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
        $amount = [250, 150, 100, 30, 25, 20, 15, 10, 5];
        foreach ($products as $productId){
            DB::table('ingredient_product')->insert([
                [
                    'id'                => Str::uuid()->toString(),
                    'product_id'        => $productId,
                    'ingredient_id'     => Arr::random($ingredients),
                    'ingredient_amount' => Arr::random($amount),
                ],
            ]);
        }
    }
}
