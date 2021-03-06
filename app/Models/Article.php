<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'summary', 'body', 'published', 'article_group_id', 'image_id'
    ];

    /**
     * Attributes that are casted to another type.
     *
     * @var array
     */
    protected $casts = [
        'article_group_id' => 'integer',
        'image_id' => 'integer',
    ];

    /**
     * Define relationships.
     */
    public function articleGroup()
    {
        return $this->belongsTo('App\Models\ArticleGroup');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Media', 'image_id');
    }

    /**
     * Custom model functions.
     */
    public function notPublished()
    {
        return !$this->attributes['published'];
    }

    /**
     * Returns the published articles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($builder)
    {
        return $builder->where('published', '=', true);
    }

    /**
     * Get slug of the current article.
     *
     */
    public function getSlug()
    {
        return str_slug($this->attributes['title']);
    }

    /**
     * Get full link to the article.
     */
    public function getLink()
    {
        return "?article=".$this->getSlug();
    }

    /**
     * Search articles and return where slug is equal.
     *
     */
    public static function whereSlug($slug)
    {
        $articles = Article::all();

        $findArticle = null;
        foreach ($articles as $article) {
            if ($article->getSlug() == $slug) {
                $findArticle = $article;
            }
        }

        return $findArticle;
    }


}
