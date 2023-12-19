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
        Schema::create('compra_ingresso', function (Blueprint $table) {
            $table->id('id_compra_ingresso');
            $table->boolean('permitir_nomeacao');
            $table->unsignedBigInteger('id_lote');
            $table->unsignedBigInteger('id_compra');

            $table->foreign('id_lote')->references('id_lote')->on('lote');
            $table->foreign('id_compra')->references('id_compra')->on('compras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compra_ingresso');
    }
};
