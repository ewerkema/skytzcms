<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'summary', 'body', 'published',
    ];

    public function setArticleGroup($articleGroup)
    {
        $this->attributes['article_group_id'] = $articleGroup->id;
        $this->save();
    }

    /**
     * Define relationships.
     */
    public function articleGroup()
    {
        return $this->belongsTo('App\ArticleGroup');
    }

    /**
     * Custom model functions.
     */
    public function notPublished()
    {
        return !$this->attributes['published'];
    }


}
