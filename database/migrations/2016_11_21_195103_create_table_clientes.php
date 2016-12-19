<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClientes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cnpj', 25);
            $table->unique('cnpj');
            $table->string('nomefantasia');
            $table->string('razaosocial');
            $table->string('telefone')->nullable();
            $table->string('whatsapp')->nullable();
            $table->string('nomegerente')->nullable();
            $table->string('tipoPessoa',1)->nullable();
            $table->string('email');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('uf',2);
            $table->string('nomeVendedor');
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
        Schema::dropIfExists('clientes');

    }
}
