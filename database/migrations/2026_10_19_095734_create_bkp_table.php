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
        Schema::create('bkp', function (Blueprint $table) {
            $table->id('id_bkp');
            $table->string('objeto')->unique();
            $table->dateTime('data_backup');
            $table->string('tipo');
            $table->unsignedBigInteger('tamanho_bytes');

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
        Schema::dropIfExists('bkp');
    }
};
