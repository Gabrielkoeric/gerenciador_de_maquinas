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
            $table->unsignedBigInteger('id_cluster')
                    ->nullable()
                    ->after('so');
            $table->foreign('id_cluster')->references('id_cluster')->on('cluster');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vm', function (Blueprint $table) {
            $table->dropForeign(['id_cluster']);
            $table->dropColumn('id_cluster');
        });
    }
};
