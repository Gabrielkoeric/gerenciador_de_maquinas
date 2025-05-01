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
    // Tabela Servidor Físico
    Schema::table('servidor_fisico', function (Blueprint $table) {
        $table->unsignedBigInteger('id_ip_wan')->default(1)->after('nome');
        $table->foreign('id_ip_wan')->references('id_ip_wan')->on('ip_wan');
    });
    
    Schema::table('servidor_fisico', function (Blueprint $table) {
        $table->dropColumn('ipwan');
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
