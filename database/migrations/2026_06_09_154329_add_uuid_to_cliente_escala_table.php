<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
            $table->uuid('uuid')
                  ->unique()
                  ->nullable()
                  ->after('chaveCliente');
        });

        // Preenche registros existentes
        DB::statement('UPDATE cliente_escala SET uuid = UUID() WHERE uuid IS NULL');
    }

    public function down()
    {
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
