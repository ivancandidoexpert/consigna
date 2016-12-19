<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Product;

class CreateTableProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codigoBling')->unique()->nullable();
            $table->string('urlImagem')->nullable();
            $table->string('nome')->nullable();
            $table->timestamps();
        });


        // inserindo produto rockinwaves.
        // evoluir para integração API futuramente com o aumento do mix de produtos
        $item = new Product;
        $item->codigoBling = 'ROCKINWAVES-P200G';
        $item->nome = 'ROCKIN\'WAVES - Máscara & Leave-In Modelador Multifuncional - 200g';
        $item->urlImagem = 'https://v0.static.betalabs.com.br/uploads/gestao_ja/produto/produto_foto/thumb/370x37071b168760608188cb1433665229811bc.jpg';
        $item->save();


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('products');
    }
}
