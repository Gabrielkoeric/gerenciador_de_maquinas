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
        Schema::create('usuario_perfil', function (Blueprint $table) {
            $table->id('id_usuario_perfil');

            $table->unsignedBigInteger('id');
            $table->unsignedBigInteger('id_perfil');

            $table->foreign('id')->references('id')->on('usuarios');
            $table->foreign('id_perfil')->references('id_perfil')->on('perfil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_perfil');
    }
};
