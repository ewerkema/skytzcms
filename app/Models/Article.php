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
        'title', 'summary', 'body', 'published', 'article_group_id',
    ];

    /**
     * Attributes that are casted to another type.
     *
     * @var array
     */
    protected $casts = [
        'article_group_id' => 'integer',
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
