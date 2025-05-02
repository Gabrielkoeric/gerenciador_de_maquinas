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
        Schema::table('usuario_servidor_fisico', function (Blueprint $table) {
            $table->boolean('principal')->default(0)->after('senha');
        });

        // Atualiza os registros existentes para garantir que a nova coluna tenha valor 0
        DB::table('usuario_servidor_fisico')->update(['principal' => 0]);
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('usuario_servidor_fisico', function (Blueprint $table) {
            $table->dropColumn('principal');
        });
    }
};
