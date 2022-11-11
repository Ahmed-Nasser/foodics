<?php

namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


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
        $this->updateIngredientStock($this->getIngredientStock($orderIngredients), $orderIngredients);
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
        return DB::table('stocks')
            ->select('ingredient_amount', 'ingredient_id')
            ->whereIn('ingredient_id', array_keys($ingredients))->get();
    }

    private function updateIngredientStock(Collection $stockIngredients, array $orderIngredients): void
    {
        $stockIngredients->map(function ($stock) use ($orderIngredients){
            if(key_exists($stock->ingredient_id, $orderIngredients)){
                $newAmount = ($stock->ingredient_amount) - ($orderIngredients[$stock->ingredient_id]);
                DB::table('stocks')
                    ->where('ingredient_id', $stock->ingredient_id)
                    ->update([
                        'ingredient_amount' => $newAmount,
                    ]);
            }
        });

    }
}
