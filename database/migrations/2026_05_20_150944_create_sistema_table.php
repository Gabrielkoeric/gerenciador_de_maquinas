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
        Schema::create('sistema', function (Blueprint $table) {
            $table->id('id_sistema');
            $table->string('nome_sistema', 255);
            $table->string('display', 255);
            $table->string('arquivo', 255);
            $table->boolean('oficial');

            $table->unsignedBigInteger('id_cliente_escala');
            $table->foreign('id_cliente_escala')->references('id_cliente_escala')->on('cliente_escala');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sistema');
    }
};
