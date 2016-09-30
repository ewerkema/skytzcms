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
        'title', 'summary', 'body', 'published', 'article_group_id',
    ];

    /**
     * Define relationships.
     */
    public function articleGroup()
    {
        return $this->belongsTo('App\Models\ArticleGroup');
    }

    /**
     * Custom model functions.
     */
    public function notPublished()
    {
        return !$this->attributes['published'];
    }


}
