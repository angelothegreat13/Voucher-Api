<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuregoldVoucherTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puregold_voucher_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('puregold_voucher_id')->constrained();
            $table->float('balance_before_trans', 8, 2);
            $table->float('amount_deducted', 8, 2);
            $table->float('balance_after_trans', 8, 2);
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
        Schema::dropIfExists('puregold_voucher_transs');
    }
}
