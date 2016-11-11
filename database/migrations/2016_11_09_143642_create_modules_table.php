<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Module;

class CreateModulesTable extends Migration
{
    private $modules = [
        'articles' => array('Nieuws', 'article_groups'),
        'forms' => array('Formulieren', 'forms'),
        'albums' => array('Foto albums', 'albums'),
        'sliders' => array('Sliders', 'sliders')
    ];


    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('template');
            $table->string('table');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        foreach ($this->modules as $module => $data) {
            Module::create([
                'name' => $data[0],
                'template' => $module,
                'table' => $data[1]
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
        Schema::dropIfExists('modules');
    }
}
