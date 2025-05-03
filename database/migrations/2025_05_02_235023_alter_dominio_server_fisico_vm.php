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
        Schema::table('vm', function (Blueprint $table) {
            $table->dropColumn('dominio');
            $table->unsignedBigInteger('id_dominio')->nullable()->after('porta');
            $table->foreign('id_dominio')->references('id_dominio')->on('dominio')->onDelete('set null');
        });

        // Remover a coluna 'dominio' e adicionar 'id_dominio' na tabela 'servidor_fisico'
        Schema::table('servidor_fisico', function (Blueprint $table) {
            $table->dropColumn('dominio');
            $table->unsignedBigInteger('id_dominio')->nullable()->after('porta');
            $table->foreign('id_dominio')->references('id_dominio')->on('dominio')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
