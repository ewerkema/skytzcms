<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Header extends Model
{
    protected $appends = ['link'];

    protected $fillable = [
        'id', 'name', 'position', 'image_id', 'slider_id', 'video', 'content', 'link_to_page', 'link_to_url', 'open_in_new_tab',
    ];

    public function getLinkAttribute()
    {
        if ($this->link_to_page != null && count($this->page))
            return $this->page()->first()->getSlug();

        return $this->link_to_url;
    }

    public function getPosition()
    {
        switch ($this->position) {
            case 0: return "left-top"; break;
            case 1: return "left-center"; break;
            case 2: return "left-bottom"; break;
            case 3: return "center-top"; break;
            case 4: return "center"; break;
            case 5: return "center-bottom"; break;
            case 6: return "right-top"; break;
            case 7: return "right-center"; break;
            case 8: return "right-bottom"; break;
        }
    }

    public function link()
    {
        if ($this->link_to_page != 0) {
            $page = Page::find($this->link_to_page);
            return $page ? page_url($page->getSlug()) : false;
        }

        if (strlen($this->link_to_url) > 0) {
            return $this->link_to_url;
        }

        return false;
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