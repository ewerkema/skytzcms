<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'content', 'published_content', 'meta_title', 'meta_desc', 'meta_keywords', 'menu', 'parent_id', 'order', 'header_id', 'pagehits',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'content' => 'array',
        'published_content' => 'array',
        'parent_id' => 'int|null',
        'header_id' => 'int|null',
        'order' => 'int',
    ];

    /**
     * Function to return the content from json array.
     *
     * @return array
     */
    public function getContent()
    {
        return $this->generateContentFromJson($this->attributes['content']);
    }

    public function getPublishedContent()
    {
        return $this->generateContentFromJson($this->attributes['published_content']);
    }

    /**
     * Define relationships.
     */
    public function header()
    {
        return $this->belongsTo('App\Models\Header', 'header_id');
    }

    public function subpages()
    {
        return $this->hasMany('App\Models\Page', 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->hasOne('App\Models\Page', 'id', 'parent_id');
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

    /**
     * Get slug of the current page.
     *
     */
    public function getSlug()
    {
        if ($this->hasParent()) {
            $parent = $this->parent()->first();
            return $parent->slug . '/' . $this->attributes['slug'];
        }

        return $this->attributes['slug'];
    }

    /**
     * Boolean check whether this page has a parent.
     */
    public function hasParent()
    {
        return $this->parent_id != 0;
    }

    /**
     * Get list of menu items with subpages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuWithSubpages()
    {
        return Cache::remember('menu-pages-with-subpages', 60, function () {
            return $this->with('subpages')->get()->where('menu', 1)->where('parent_id', NULL)->sortBy('order');
        });
    }

    /**
     * Get list of menu items without subpages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuWithoutSubpages()
    {
        return Cache::remember('menu-pages-without-subpages', 60, function () {
            return $this->all()->where('menu', 1)->where('parent_id', NULL)->sortBy('order');
        });
    }

    /**
     * Get list of menu pages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenu()
    {
        return Cache::remember('menu-pages', 60, function() {
            return $this->all()->where('menu', 1)->sortBy('order');
        });
    }

    /**
     * Get list of non menu pages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getNonMenu()
    {
        return Cache::remember('non-menu-pages', 60, function() {
            return $this->all()->where('menu', 0)->sortBy('title');
        });
    }

    /**
     * Get the page together with the slug for display in the editor.
     *
     * @return array
     */
    public function getEditorLink()
    {
        return array($this->attributes['title'].' ('.$this->getSlug().')', '/' . $this->getSlug());
    }

    /**
     * Get editor links for all pages
     *
     * @return \Illuminate\Support\Collection
     */
    public function getEditorLinks()
    {
        return Cache::remember('editor-links', 60, function() {
            return $this->all()->map(function($page) {
                return $page->getEditorLink();
            });
        });
    }

    /**
     * Function to return the content from json array.
     *
     * @param array $contentArray
     * @return array
     */
    public function generateContentFromJson($contentArray)
    {
        $blocks = json_decode($contentArray, true);

        usort($blocks, function ($a, $b) {
            return 12*($a['y'] - $b['y']) + ($a['x'] - $b['x']);
        });

        $currentRow = 0;
        $offset = 0;
        $content = array();
        foreach ($blocks as $id => $block) {
            if ($currentRow != $block['y']) {
                $currentRow = $block['y'];
                $offset = 0;
            }

            $content[$currentRow][$id] = $blocks[$id];
            $content[$currentRow][$id]['offset'] = $block['x'] - $offset;

            if ($currentRow == $block['y']) {
                $offset = $block['x'] + $block['width'];
            }
        }

        return $content;
    }
}
