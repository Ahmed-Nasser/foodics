<?php

namespace App\Observers;

use App\Jobs\SendEmailJob;
use App\Models\Ingredient;
use App\Models\Stock;
use Illuminate\Support\Facades\Log;
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
        DB::beginTransaction();
        try {
            $this->updateTransactionStock($stock);
            $this->checkStockStatus($stock);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    private function checkStockStatus(Stock $stock): void
    {
        $percentage = $stock->initial_ingredient_amount * 0.5;
        if($stock->ingredient_amount <= $percentage && $stock->notified == 0){
            $ingredient = $this->getIngredient($stock->ingredient_id);
            dispatch(new SendEmailJob($ingredient));
            $this->updateNotifiedStock($stock, 1);
            Log::info('Email has been sent....');
        } elseif (($stock->ingredient_amount == $stock->initial_ingredient_amount) && $stock->notified == 1){
            //Stock ingredient has been credited
            $this->updateNotifiedStock($stock, 0);
        }

    }

    private function getIngredient(string $ingredientId){
        return Ingredient::find($ingredientId);
    }

    private function updateNotifiedStock(Stock $stock, bool $notified): void
    {
        $stock::where('id', $stock->id)->update(['notified' => $notified]);
    }

    private function updateTransactionStock(Stock $stock): void
    {
        DB::table('transaction_stock')
            ->where('stock_id', $stock->id)
            ->update(
            [
                'type' => 'debit',
                'consumed_amount' =>  $stock->getOriginal('ingredient_amount') - $stock->ingredient_amount,
                'old_amount' => $stock->getOriginal('ingredient_amount'),
            ]
        );
    }
}
