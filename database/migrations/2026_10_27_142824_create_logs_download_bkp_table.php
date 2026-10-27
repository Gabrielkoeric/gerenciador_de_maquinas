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
        Schema::create('logs_download_bkp', function (Blueprint $table) {
            $table->id('id_logs_download_bkp');
            $table->string('objeto', 255);
            $table->string('nome_cliente', 255);
            $table->dateTime('data_hora')->useCurrent();
            $table->string('ip_address', 45)->nullable();

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs_download_bkp');
    }
};
