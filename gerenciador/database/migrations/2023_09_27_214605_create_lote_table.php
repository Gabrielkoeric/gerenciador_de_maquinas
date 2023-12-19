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
        Schema::create('lote', function (Blueprint $table) {
            $table->id('id_lote');
            $table->integer('numero_lote');
            $table->integer('quantidade_lote');
            $table->integer('quantidade_lote_disponivel');
            $table->boolean('ativo');
            $table->float('adicional_lote');
            $table->unsignedBigInteger('id_ingressos');
            $table->timestamps();

            $table->foreign('id_ingressos')->references('id_ingressos')->on('ingressos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lote');
    }
};
