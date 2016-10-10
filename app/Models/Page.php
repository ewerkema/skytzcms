<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'content', 'meta_title', 'meta_desc', 'meta_keywords', 'menu', 'parent_id', 'order', 'header_image_id', 'pagehits',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'content' => 'array',
    ];

    
    /**
     * Define relationships.
     */
    public function header()
    {
        return $this->hasOne('Media');
    }
}
