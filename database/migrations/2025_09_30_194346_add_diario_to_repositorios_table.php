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
        Schema::table('repositorios', function (Blueprint $table) {
            $table->tinyInteger('diario')
                  ->default(0)
                  ->after('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('repositorios', function (Blueprint $table) {
            $table->dropColumn('diario');
        });
    }
};
