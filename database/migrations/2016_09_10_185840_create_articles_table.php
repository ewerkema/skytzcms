<?php

use App\Models\Article;
use App\Models\ArticleGroup;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
            $table->integer('article_group_id')->unsigned();
            $table->string('title');
            $table->text('summary');
            $table->text('body');
            $table->boolean('published')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('article_group_id')->references('id')->on('article_groups');
        });

        ImportTable::import('skytz_newsitems', function ($article) {
            $date = DateTime::createFromFormat('d-m-Y - H:i:s', $article->date);

            try {
                $articleGroup = ArticleGroup::findOrFail($article->newsid);
            } catch (ModelNotFoundException $e) {
                dd("Couldn't find article group with ID ".$article->newsid.": ".$e->getMessage());
            }

            $article = Article::create([
                'title' => $article->title,
                'summary' => '',
                'body' => $article->content,
                'article_group_id' => $articleGroup->id,
            ]);

            $article->setUpdatedAt($date->format('Y-m-d H:i:s'));
            $article->setCreatedAt($date->format('Y-m-d H:i:s'));
            $article->save();
        });
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

}
