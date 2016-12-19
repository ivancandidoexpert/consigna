<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTransactions extends Migration
{
     /*
     * @return void
     */
     public function up()
    {
        //
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cliente_id')->unsigned()->index()->nullable();
            $table->string('tipoTransacao',25)->nullable();
            $table->string('codPedidoExterno',30)->nullable();
            $table->timestamps();
        });


        // connect transaction to products
        Schema::create('product_transaction', function (Blueprint $table) {
            $table->integer('product_id')->unsigned()->index();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->integer('transaction_id')->unsigned()->index();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
            $table->integer('quantidade')->unsigned();
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
         Schema::dropIfExists('product_transaction');
         Schema::dropIfExists('transactions');
         
    }
}
