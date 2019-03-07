<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'colorbox', 'order'
    ];

    protected $casts = [
        'order' => 'array'
    ];

    public function getOrderedMedia()
    {
        $media = $this->media()->get();

        $orderedMedia = Collection::make([]);
        if ($media->count()) {
            $order = $this->order;

            if (sizeof($order) > 0) {
                foreach ($order as $id) {
                    $image = $media->filter(function($image) use ($id) {
                        return $image->getKey() == $id;
                    })->first();

                    if ($image != null)
                        $orderedMedia->push($image);
                }
            }

            foreach ($media->diff($orderedMedia) as $leftOver) {
                $orderedMedia->push($leftOver);
            }
        }

        return $orderedMedia;
    }

    /**
     * Search albums and return where slug is equal.
     *
     */
    public static function whereSlug($slug)
    {
        $albums = Album::all();

        $findAlbum = null;
        foreach ($albums as $album) {
            if ($album->getSlug() == $slug) {
                $findAlbum = $album;
            }
        }

        return $findAlbum;
    }

    /**
     * Get slug of the current album.
     *
     */
    public function getSlug()
    {
        return str_slug($this->attributes['name']);
    }

    /**
     * Get full link to the album.
     */
    public function getLink()
    {
        return "?album=".$this->getSlug();
    }

    /**
     * Define relationships.
     */
    public function media()
    {
        return $this->belongsToMany('App\Models\Media');
    }
}
