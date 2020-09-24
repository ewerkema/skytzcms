<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $fillable = [
        'title', 'page_id', 'parent_id', 'order', 'link', 'open_in_new_tab',
    ];

    protected $casts = [
        'open_in_new_tab' => 'boolean',
        'order' => 'int',
    ];

    protected $appends = ['url', 'linkName'];

    protected $with = ['page'];

    /**
     * Boolean check whether this page has a parent.
     *
     * @return bool
     */
    public function hasParent()
    {
        return $this->parent != null && $this->parent->count() > 0;
    }

    /**
     * Get the URL of the current menu item.
     *
     * @return string
     */
    public function getUrl()
    {
        if (!is_null($this->attributes['page_id'])) {
            $parent = $this->hasParent() ? $this->parent->page : null;

            return $this->page->getUrl($parent);
        }

        return $this->attributes['link'];
    }

    /**
     * Get the URL attribute.
     *
     * @return string
     */
    public function getUrlAttribute()
    {
        return $this->getUrl();
    }

    /**
     * Return the page title if its set, else return the manual set title.
     *
     * @return string
     */
    public function getLinkNameAttribute()
    {
        if (array_key_exists('title', $this->attributes) && !is_null($this->attributes['title']) && strlen($this->attributes['title']) > 0) {
            return $this->attributes['title'];
        }

        if (!is_null($this->attributes['page_id'])) {
            return $this->page->title;
        }

        return $this->attributes['title'];
    }

    /**
     * Check for empty input parent page and cast to NULL if true.
     *
     * @param $value
     */
    public function setParentIdAttribute($value)
    {
        $this->attributes['parent_id'] = (empty($value) || !$value) ? NULL : $value;
    }

    public function page()
    {
        return $this->belongsTo('App\Models\Page');
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\MenuItem', 'parent_id', 'id');
    }

    public function subItems()
    {
        return $this->hasMany('App\Models\MenuItem', 'parent_id', 'id')->orderBy('order');
    }
}
