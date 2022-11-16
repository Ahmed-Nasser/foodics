<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Stock;
use Illuminate\Support\Collection;

class OrderObserver
{
    public bool $afterCommit = true;

    /**
     * Handle the Order "created" event.
     *
     * @param Order $order
     * @return void
     */
    public function created(Order $order): void
    {
        $orderIngredients = $this->calculateOrderIngredients($order);
        $ingredientStock = $this->getIngredientStock($orderIngredients);
        $this->updateIngredientStock($ingredientStock, $orderIngredients);
    }

    private function calculateOrderIngredients(Order $order): array
    {
        $orderIngredients = [];
        $order->products->map(function ($product) use (&$orderIngredients) {
           $product->ingredients->map(function ($ingredient) use ($product, &$orderIngredients) {
               if(key_exists($ingredient->id, $orderIngredients)){
                   $orderIngredients[$ingredient->id] = ($orderIngredients[$ingredient->id]) + ($ingredient->pivot->ingredient_amount * $product->pivot->quantity / 1000);
               } else{
                   $orderIngredients[$ingredient->id] = ($ingredient->pivot->ingredient_amount) * ($product->pivot->quantity / 1000);
               }
           });
        });
        return $orderIngredients;
    }

    private function getIngredientStock(array $ingredients): Collection
    {
        return Stock::select('ingredient_amount', 'ingredient_id')
            ->whereIn('ingredient_id', array_keys($ingredients))->get();
    }

    private function updateIngredientStock(Collection $ingredientStock, array $orderIngredients): void
    {
        $ingredientStock->map(function ($stock) use ($orderIngredients){
            if(key_exists($stock->ingredient_id, $orderIngredients)){
                $newAmount = ($stock->ingredient_amount) - ($orderIngredients[$stock->ingredient_id]);
                Stock::find($stock->ingredient_id)->update(['ingredient_amount' => $newAmount]);
            }
        });

    }
}
