<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('produtos_disponiveis', function (Blueprint $table) {
            $table->id('id_produtos_disponiveis');
            $table->integer('quantidade');
            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('id_produto_estoque');
            $table->timestamps();

            $table->foreign('id')->references('id')->on('usuarios');
            $table->foreign('id_produto_estoque')->references('id_produto_estoque')->on('estoques');
        });

        // Trigger para atualizar a tabela produtos_disponiveis

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos_disponiveis');
    }
};
