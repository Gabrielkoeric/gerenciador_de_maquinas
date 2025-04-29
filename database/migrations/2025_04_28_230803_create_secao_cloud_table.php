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
        Schema::create('secao_cloud', function (Blueprint $table) {
            $table->id('id_secao_cloud');
            $table->string('usuario');
            $table->string('senha');

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
        Schema::dropIfExists('secao_cloud');
    }
};
