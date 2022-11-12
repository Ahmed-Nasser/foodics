<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            MerchantSeeder::class,
            ProductSeeder::class,
            IngredientSeeder::class,
            StockSeeder::class,
            IngredientProductSeeder::class,
        ]);
    }
}
