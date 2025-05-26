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
        Schema::table('servidor_fisico', function (Blueprint $table) {
            $table->string('serial')->nullable()->after('tipo');
            $table->string('mac')->nullable()->after('serial');
        });
    }

    public function down()
    {
        Schema::table('servidor_fisico', function (Blueprint $table) {
            $table->dropColumn(['serial', 'mac']);
        });
    }
};
