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
        Schema::create('usuario_servico', function (Blueprint $table) {
            $table->id('id_usuario_servico');
            $table->string('usuario');
            $table->string('senha');
            $table->timestamps();

            $table->unsignedBigInteger('id_servico_vm');
            $table->foreign('id_servico_vm')->references('id_servico_vm')->on('servico_vm');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_servico');
    }
};
