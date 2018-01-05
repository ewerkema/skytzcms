<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Module::where('template', 'projects')->count()) {
            Module::create([
                'name' => 'Projecten',
                'template' => 'projects',
                'table' => 'project_groups'
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if ($module = Module::where('template', 'projects')->first()) {
            $module->delete();
        }
    }
}
