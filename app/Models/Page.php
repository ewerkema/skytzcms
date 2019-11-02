<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'content', 'published_content', 'meta_title', 'meta_desc', 'meta_keywords', 'header_id', 'pagehits',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'content' => 'array',
        'published_content' => 'array',
        'header_id' => 'int|null',
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

    /**
     * Get slug of the current page.
     *
     * @param Page|null $parent
     * @return string
     */
    public function getSlug($parent = null)
    {
        if ($parent != null) {
            return $parent->slug . '/' . $this->attributes['slug'];
        }

        return $this->attributes['slug'];
    }

    /**
     * Get the URL of the current page.
     *
     * @param null $parent
     * @return string
     */
    public function getUrl($parent = null)
    {
        return page_url($this->getSlug($parent));
    }

    /**
     * Get list of menu pages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenu()
    {
        return Cache::remember('menu-pages', 60, function() {
            return Page::all()->filter(function(Page $page) {
                return $page->menuItems->count() > 0;
            })->sortBy('title');
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
            return Page::all()->filter(function(Page $page) {
                return $page->menuItems->count() == 0;
            })->sortBy('title');
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

    /**
     * Return the menu items of the current page.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function menuItems()
    {
        return $this->hasMany('App\Models\MenuItem', 'page_id', 'id');
    }
}
