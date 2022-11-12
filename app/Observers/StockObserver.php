<?php

namespace App\Observers;

use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockObserver
{
    /**
     * Handle the Stock "updated" event.
     *
     * @param Stock $stock
     * @return void
     */
    public function updated(Stock $stock): void
    {
        $stockTransaction = $this->getTransactionStock($stock->id);

        if (count($stockTransaction) == 0){
            $this->storeTransactionStockIfNotExist($stock);
        } else{
            $this->updateTransactionStock($stock, $stockTransaction->first());
        }

        Log::debug($stock->toJson()."\r\n".$stockTransaction->toJson());
    }

    private function updateTransactionStock(Stock $stock, $stockTransaction): void
    {
        DB::table('transaction_stock')->upsert(
            [
                'id' => $stockTransaction->id,
                'stock_id' => $stock->id,
                'type' => 'debit',
                'consumed_amount' => $stockTransaction->old_amount - $stock->ingredient_amount,
                'old_amount' => 0,
            ],

            ['stock_id' => $stock->id, 'id' => $stockTransaction->id],

            [
                'id' => $stockTransaction->id,
                'stock_id' => $stock->id,
                'type' => 'debit',
                'consumed_amount' => $stockTransaction->old_amount - $stock->ingredient_amount,
                'old_amount' => 0,
            ]
        );
    }

    private function getTransactionStock(string $stockId): Collection
    {
        return DB::table('transaction_stock')->where('stock_id', $stockId)->get();
    }

    private  function storeTransactionStockIfNotExist(Stock $stock): void
    {
        DB::table('transaction_stock')->insert(
            [
                'id' => Str::uuid()->toString(),
                'stock_id' => $stock->id,
                'type' => 'credit',
                'consumed_amount' => 0,
                'old_amount' => $stock->ingredient_amount,
            ]
        );

    }


}
