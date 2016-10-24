<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'slug', 'title', 'content', 'published_content', 'meta_title', 'meta_desc', 'meta_keywords', 'menu', 'parent_id', 'order', 'header_image_id', 'pagehits',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'content' => 'array',
        'published_content' => 'array',
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
     * @return array
     */
    public function getEditorLink()
    {
        return array($this->attributes['title'].' ('.$this->attributes['slug'].')', $this->attributes['slug']);
    }
    
    /**
     * Define relationships.
     */
    public function header()
    {
        return $this->hasOne('Media');
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
