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
            $table->string('config_ws')->nullable()->after('remoteapp');
            $table->string('data_atualizacao')->nullable()->after('config_ws');
            $table->string('licenciado_ate')->nullable()->after('data_atualizacao');
        });
    }

    public function down()
    {
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->dropColumn('config_ws');
            $table->dropColumn('data_atualizacao');
            $table->dropColumn('licenciado_ate');
        });
    }
};
