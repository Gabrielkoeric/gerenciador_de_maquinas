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
    public function up(): void
    {
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->renameColumn('sigla', 'apelido');
        });
    }

    public function down(): void
    {
        Schema::table('cliente_escala', function (Blueprint $table) {
            $table->renameColumn('apelido', 'sigla');
        });
    }
};
