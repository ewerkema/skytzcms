<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ImportOldCms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (config('skytz.old_cms'))
            DB::unprepared(file_get_contents(config('skytz.old_cms')));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dropAllTables();
    }

    /**
     * Drop all existing tables except migrations.
     *
     * @return void
     */
    public function dropAllTables()
    {
        $tableNames = Schema::getConnection()->getDoctrineSchemaManager()->listTableNames();
        foreach ($tableNames as $name) {
            if ($name == 'migrations') {
                continue;
            }
            Schema::drop($name);
        }
    }
}
