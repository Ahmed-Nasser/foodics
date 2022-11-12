<?php

namespace App\Observers;

use App\Models\Stock;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

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
    }

    private function updateTransactionStock(Stock $stock, $stockTransaction): void
    {
        DB::table('transaction_stock')->upsert(
            [
                'id' => $stockTransaction->id,
                'stock_id' => $stock->id,
                'type' => 'debit',
                'consumed_amount' =>  $stock->getOriginal('ingredient_amount') - $stock->ingredient_amount,
                'old_amount' => $stock->getOriginal('ingredient_amount'),
            ],

            ['stock_id' => $stock->id, 'id' => $stockTransaction->id],

            [
                'id' => $stockTransaction->id,
                'stock_id' => $stock->id,
                'type' => 'debit',
                'consumed_amount' => $stock->getOriginal('ingredient_amount') - $stock->ingredient_amount,
                'old_amount' => $stock->getOriginal('ingredient_amount'),
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
                'type' => 'debit',
                'consumed_amount' => $stock->getOriginal('ingredient_amount') - $stock->ingredient_amount,
                'old_amount' => $stock->getOriginal('ingredient_amount'),
            ]
        );

    }


}
