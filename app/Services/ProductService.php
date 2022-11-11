<?php

namespace App\Services;

use Illuminate\Support\Arr;

class ProductService
{
    public function prepareProductQuantity(array $products): array
    {
        return Arr::collapse(collect($products)->map(function ($product){
            return [
                $product['productId'] => ['quantity' => $product['quantity']]
            ];
        })->toArray());
    }
}
