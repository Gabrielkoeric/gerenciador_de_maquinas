<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('ALTER TABLE async_tasks MODIFY log MEDIUMTEXT NULL');
    }

    public function down()
    {
        DB::statement('ALTER TABLE async_tasks MODIFY log TEXT NULL');
    }
};