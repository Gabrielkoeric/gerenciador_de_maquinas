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
    // Tabela VM
    Schema::table('vm', function (Blueprint $table) {
        $table->unsignedBigInteger('id_ip_lan')->default(1)->after('nome');
        $table->foreign('id_ip_lan')->references('id_ip_lan')->on('ip_lan');
    });

    // Tabela Servidor Físico
    Schema::table('servidor_fisico', function (Blueprint $table) {
        $table->unsignedBigInteger('id_ip_lan')->default(1)->after('ipwan');
        $table->foreign('id_ip_lan')->references('id_ip_lan')->on('ip_lan');
    });

    Schema::table('vm', function (Blueprint $table) {
        $table->dropColumn('iplan');
    });
    
    Schema::table('servidor_fisico', function (Blueprint $table) {
        $table->dropColumn('iplan');
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
