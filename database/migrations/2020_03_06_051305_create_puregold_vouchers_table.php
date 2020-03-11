<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePuregoldVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('puregold_vouchers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('code',300)->unique();	
            $table->string('name',50);
            $table->string('description');
            $table->float('amount', 8, 2);	
            $table->float('balance', 8, 2);	
            $table->tinyInteger('is_used')->default(0)->comment('0 not used , 1 used');
            $table->dateTime('expiration_date');
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
        Schema::dropIfExists('puregold_vouchers');
    }
}
