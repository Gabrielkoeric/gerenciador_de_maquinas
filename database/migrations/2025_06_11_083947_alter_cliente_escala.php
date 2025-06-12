<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('cliente_escala', function (Blueprint $table) {
        $table->integer('porta_rdp')->nullable()->after('remoteapp');
    });
}

public function down()
{
    Schema::table('cliente_escala', function (Blueprint $table) {
        $table->dropColumn(['porta_rdp']);
    });
}
};
