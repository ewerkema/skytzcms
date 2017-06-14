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

            foreach ($order as $id) {
                $image = $media->filter(function($image) use ($id) {
                    return $image->getKey() == $id;
                })->first();

                if ($image != null)
                    $orderedMedia->push($image);
            }

            foreach ($media->diff($orderedMedia) as $leftOver) {
                $orderedMedia->push($leftOver);
            }
        }

        return $orderedMedia;
    }

    /**
     * Define relationships.
     */
    public function media()
    {
        return $this->belongsToMany('App\Models\Media');
    }
}
