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
        Schema::create('permissao_bkp_cliente', function (Blueprint $table) {
            $table->id('id_permissao_bkp_cliente');
            $table->unsignedSmallInteger('ano_referencia');
            $table->string('numero_sd', 50);
            $table->dateTime('data_liberacao');

            $table->unsignedBigInteger('id_cliente_escala');
            $table->foreign('id_cliente_escala')->references('id_cliente_escala')->on('cliente_escala');

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('usuarios');

            $table->unsignedBigInteger('id_usuario_liberacao');
            $table->foreign('id_usuario_liberacao')->references('id')->on('usuarios');
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissao_bkp_cliente');
    }
};
