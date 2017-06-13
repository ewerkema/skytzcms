<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'colorbox',
    ];

    /**
     * Define relationships.
     */
    public function media()
    {
        return $this->belongsToMany('App\Models\Media');
    }
}
