<?php

namespace App\Observers;

use App\Jobs\SendEmailJob;
use App\Models\Ingredient;
use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
            $this->addTransactionStock($stock);
            $this->checkStockStatus($stock);
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw $exception;
        }

    }

    private function checkStockStatus(Stock $stock): void
    {
        $fiftyPercentOfIngredientAmount = $stock->initial_ingredient_amount * 0.5;
        if($stock->ingredient_amount <= $fiftyPercentOfIngredientAmount && $stock->notified == 0){
            $ingredient = $this->getIngredient($stock->ingredient_id);
            dispatch(new SendEmailJob($ingredient));
            $this->updateNotifiedStock($stock, 1);
            Log::info('Email has been sent....');
        }

    }

    private function getIngredient(string $ingredientId){
        return Ingredient::find($ingredientId);
    }

    private function updateNotifiedStock(Stock $stock, bool $notified): void
    {
        $stock::where('id', $stock->id)->update(['notified' => $notified]);
    }

    private function addTransactionStock(Stock $stock): void
    {
        DB::table('transaction_stock')
            ->where('stock_id', $stock->id)
            ->insert(
            [
                'id'              => Str::uuid()->toString(),
                'stock_id'        => $stock->id,
                'type'            => 'debit',
                'consumed_amount' =>  $stock->getOriginal('ingredient_amount') - $stock->ingredient_amount,
                'old_amount'      => $stock->getOriginal('ingredient_amount'),
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]
        );
    }
}
