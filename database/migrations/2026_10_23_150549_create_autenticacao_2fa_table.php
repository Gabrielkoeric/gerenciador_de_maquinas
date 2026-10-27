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
        Schema::create('autenticacao_2fa', function (Blueprint $table) {
            $table->id('id_autenticacao_2fa');

            $table->string('codigo_hash', 255);
            $table->dateTime('expira_em');
            $table->unsignedTinyInteger('tentativas')->default(0);
            $table->dateTime('validado_em')->nullable();

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('usuarios');

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
        Schema::dropIfExists('autenticacao_2fa');
    }
};
