<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = [
        'path', 'title', 'meta_title', 'meta_desc', 'meta_keywords', 'menu', 'parent_id', 'order', 'header_image_id', 'pagehits',
    ];

    public function setHeader($media)
    {
        $this->attributes['header_image_id'] = $media->id;
        $this->save();
    }

    /**
     * Define relationships.
     */
    public function header()
    {
        return $this->hasOne('Media');
    }
}
