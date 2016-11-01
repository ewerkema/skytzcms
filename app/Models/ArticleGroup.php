<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    /**
     * Define relationships.
     */
    public function articles()
    {
        return $this->hasMany('App\Models\Article', 'article_group_id');
    }

}
