<?php

namespace Database\Seeders;

use App\Models\Stock;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionStockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $stocks = Stock::all();
        foreach ($stocks as $stock){
            DB::table('transaction_stock')->insert([
                'id'              => Str::uuid()->toString(),
                'stock_id'        => $stock->id,
                'type'            => 'credit',
                'consumed_amount' => 0,
                'old_amount'      => $stock->initial_ingredient_amount,
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }
    }
}
