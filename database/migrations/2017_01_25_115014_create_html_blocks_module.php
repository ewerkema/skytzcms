<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHtmlBlocksModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('html_blocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('html')->nullable();
            $table->timestamps();
        });

        if (!Module::where('table', 'html_blocks')->count()) {
            Module::create([
                'name' => 'HTML blokken',
                'template' => 'html_blocks',
                'table' => 'html_blocks'
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
        Schema::drop('html_blocks');
    }
}
