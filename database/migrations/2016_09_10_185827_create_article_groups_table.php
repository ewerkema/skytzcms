<?php

use App\Models\ArticleGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
        });

        if (config('skytz.old_cms'))
            $this->importArticleGroups();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('article_groups');
    }

    /**
     * Import article groups from old database.
     *
     * @return void
     */
    public function importArticleGroups()
    {
        $articleGroups = DB::table('skytz_newssubjects')->get();
        $articleGroups->each(function ($articleGroup) {
            ArticleGroup::create([
               'title' => $articleGroup->title,
            ]);
        });
        Schema::drop('skytz_newssubjects');
    }
}
