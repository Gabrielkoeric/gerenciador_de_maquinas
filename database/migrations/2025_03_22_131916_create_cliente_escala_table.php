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
        Schema::create('cliente_escala', function (Blueprint $table) {
            $table->id('id_cliente_escala');
            $table->string('nome');
            $table->string('licenca');
            $table->string('coletor');
            $table->string('desktop');
            $table->string('remoteapp')->nullable();
            $table->boolean('ativo')->default(true);
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
        Schema::dropIfExists('cliente_escala');
    }
};
