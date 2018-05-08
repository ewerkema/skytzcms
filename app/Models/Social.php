<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type', 'url',
    ];

    const TYPES = [
        'post' => 'Facebook post',
        'video' => 'Facebook video',
        'comments' => 'Facebook comment plugin',
        'embedded_comment' => 'Facebook embedded comment',
        'like' => 'Like button',
        'page' => 'Facebook page',
    ];

    /**
     * Get types of social media.
     *
     * @return array
     */
    public static function getTypes()
    {
        return Social::TYPES;
    }
}
