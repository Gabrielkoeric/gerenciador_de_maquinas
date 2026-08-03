<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
        {
            Schema::create('api_logs', function (Blueprint $table) {
                $table->id('id_api_logs');
                $table->dateTime('data_hora', 6);
                $table->char('uuid', 36)->nullable();
                $table->string('ip', 45);
                $table->string('metodo', 10);
                $table->string('rota', 500);
                $table->unsignedSmallInteger('status');
                $table->unsignedInteger('tempo_ms');
                $table->unsignedInteger('tamanho_resposta')->nullable();
                $table->text('user_agent')->nullable();            
            });
        }

    public function down(): void
    {
        Schema::dropIfExists('api_logs');
    }
};
