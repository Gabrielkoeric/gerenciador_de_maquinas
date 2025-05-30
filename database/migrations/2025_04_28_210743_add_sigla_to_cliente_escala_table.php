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
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->string('sigla')->nullable()->after('nome'); // exemplo: adicionando depois do 'nome'
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->dropColumn('sigla');
        });
    }
};
