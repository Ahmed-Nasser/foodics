<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'id' => Str::uuid()->toString(),
            'name' => Arr::random(['Burger', 'Pizza']),
        ];
    }
}
