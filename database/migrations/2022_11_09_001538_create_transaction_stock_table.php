<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_stock', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('stock_id');
            $table->enum('type', ['debit', 'credit']);
            $table->integer('consumed_amount');
            $table->integer('old_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_stock');
    }
};
