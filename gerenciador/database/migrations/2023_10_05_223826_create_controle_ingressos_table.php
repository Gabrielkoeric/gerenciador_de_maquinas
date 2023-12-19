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
        Schema::create('controle_ingressos', function (Blueprint $table) {
            $table->id('id_controle_ingresso');
            $table->string('hash');
            $table->string('qrcode');
            $table->boolean('check-in');
            $table->boolean('check-out');
            $table->unsignedBigInteger('id_compra_ingresso');
            $table->unsignedBigInteger('id');
            $table->timestamps();

            $table->foreign('id_compra_ingresso')->references('id_compra_ingresso')->on('compra_ingresso');
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
        Schema::dropIfExists('controle_ingressos');
    }
};
