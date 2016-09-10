<?php

use App\Article;
use App\ArticleGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('summary');
            $table->text('body');
            $table->integer('article_group_id');
            $table->foreign('article_group_id')->references('id')->on('article_groups');
            $table->boolean('published')->default(true);
            $table->timestamps();
        });

        if (config('skytz.old_cms'))
            $this->importArticles();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }

    /**
     * Import existing articles from old table.
     *
     * @return void
     */
    public function importArticles()
    {
        $articles = DB::table('skytz_newsitems')->get();
        $articles->each(function($article) {
            $date = DateTime::createFromFormat('d-m-Y - H:i:s', $article->date);

            $articleGroup = ArticleGroup::find($article->newsid);
            $article = Article::create([
                'title' => $article->title,
                'summary' => '',
                'body' => $article->content,
            ]);

            $article->article_group_id = $articleGroup->id;
            $article->setUpdatedAt($date->format('Y-m-d H:i:s'));
            $article->setCreatedAt($date->format('Y-m-d H:i:s'));
            $article->save();
        });
        Schema::drop('skytz_newsitems');
    }
}
