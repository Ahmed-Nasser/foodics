<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $productNames = [
            'mexican-burger-Large',
            'mexican-burger-medium',
            'mexican-burger-small',
            'ranch-pizza-large',
            'ranch-pizza-medium',
            'ranch-pizza-small',
        ];

        foreach ($productNames as $productName) {
            Product::factory()->create(['name' => $productName]);
        }
    }
}
