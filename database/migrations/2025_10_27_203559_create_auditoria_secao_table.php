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
    public function up(): void
    {
        Schema::create('auditoria_secao', function (Blueprint $table) {
            $table->id('id_auditoria_secao');
            $table->unsignedBigInteger('id_cliente_escala');
            $table->unsignedBigInteger('id_horario_auditoria');
            $table->integer('quantidade')->default(0);

            // Chaves estrangeiras
            $table->foreign('id_cliente_escala')->references('id_cliente_escala')->on('cliente_escala');
            $table->foreign('id_horario_auditoria')->references('id_horario_auditoria')->on('horario_auditoria');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auditoria_secao');
    }
};
