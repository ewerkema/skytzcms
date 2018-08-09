<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $appends = ['link'];

    protected $fillable = [
        'id', 'name', 'image_id', 'slider_id', 'video', 'content', 'link_to_page', 'link_to_url', 'open_in_new_tab',
    ];

    public function getLinkAttribute()
    {
        if ($this->link_to_page != null && count($this->page))
            return $this->page()->first()->getSlug();

        return $this->link_to_url;
    }

    /**
     * Define relationships.
     */
    public function image()
    {
        return $this->belongsTo('App\Models\Media', 'image_id');
    }

    public function slider()
    {
        return $this->belongsTo('App\Models\Slider', 'slider_id');
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Page', 'link_to_page');
    }
}