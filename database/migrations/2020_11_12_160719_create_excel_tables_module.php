<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExcelTablesModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_tables', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('table')->nullable();
            $table->timestamps();
        });

        if (!Module::where('template', 'excel_tables')->count()) {
            Module::create([
                'name' => 'Excel tabellen',
                'template' => 'excel_tables',
                'table' => ''
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
        //
    }
}
