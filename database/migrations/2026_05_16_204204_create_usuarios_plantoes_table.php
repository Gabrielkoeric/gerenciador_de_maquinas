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
        Schema::create('usuarios_plantoes', function (Blueprint $table) {
            $table->id('id_usuarios_plantoes');
            $table->dateTime('inicio');
            $table->dateTime('fim');
            $table->string('handle', 100)->nullable();
            $table->string('origem', 50)->default('api');

            $table->unsignedBigInteger('id');
            $table->foreign('id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_plantoes');
    }
};
