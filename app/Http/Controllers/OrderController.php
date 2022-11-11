<?php

namespace App\Http\Controllers;

use App\Models\IngredientProduct;
use App\Models\Order;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        // Validate request payload.
        $validated = $request->validate([
            'products'             => ['array', 'required'],
            'products.*.productId' => ['required', 'string', 'exists:products,id'],
            'products.*.quantity'  => ['required', 'integer', 'min:1'],
        ]);

        //store order.
        $order = new  Order();
        $order->save();

        //store order products.
        $products = $this->prepareProductQuantity($validated['products']);
        $order->products()->attach($products);

        //check order products (product) ingredients.
        $productIngredients = $this->getProductIngredients($products);

        //calculate ingredient consumption for the ordered quantity
        $orderIngredients = $this->calculateOrderIngredients($validated['products'], $productIngredients);
        $stockIngredients = $this->getIngredientStock($orderIngredients);

        // update stock
        $this->updateIngredientStock($stockIngredients, $orderIngredients);

        //TODO:: update stock_transaction
    }

    private function prepareProductQuantity(array $products): array
    {
        return Arr::collapse(collect($products)->map(function ($product){
            return [
                $product['productId'] => ['quantity' => $product['quantity']]
            ];
        })->toArray());
    }

    private function getProductIngredients(array $products): Collection
    {
        return IngredientProduct::WhereIn('product_id', array_keys($products))->get();
    }

    private function getIngredientStock(array $ingredients): \Illuminate\Support\Collection
    {
        return DB::table('stocks')
            ->select('ingredient_amount', 'ingredient_id')
            ->whereIn('ingredient_id', array_keys($ingredients))->get();
    }

    private function calculateOrderIngredients(array $products, Collection $productIngredients): array
    {
        $orderIngredients = [];
        collect($products)->map(function ($product) use ($productIngredients, &$orderIngredients){
            return collect($productIngredients)->where('product_id', $product['productId'])
                ->map(function ($ingredient) use ($product, &$orderIngredients){
                    if(key_exists($ingredient->ingredient_id, $orderIngredients)){
                        $orderIngredients[$ingredient->ingredient_id] = ($orderIngredients[$ingredient->ingredient_id]) + ($ingredient->ingredient_amount * $product['quantity'] / 1000);
                    } else{
                        $orderIngredients[$ingredient->ingredient_id] = ($ingredient->ingredient_amount) * ($product['quantity'] / 1000);
                    }
                });
        });

        return $orderIngredients;
    }

    private function updateIngredientStock(\Illuminate\Support\Collection $stockIngredients, array $orderIngredients): bool
    {
        $stockIngredients->map(function ($stock) use ($orderIngredients){
            if(key_exists($stock->ingredient_id, $orderIngredients)){
                $new_amount = ($stock->ingredient_amount) - ($orderIngredients[$stock->ingredient_id]);
                DB::table('stocks')
                    ->where('ingredient_id', $stock->ingredient_id)
                    ->update([
                        'ingredient_amount' => $new_amount,
                    ]);
            }
        });

        return true;

    }


}
