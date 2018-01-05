<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectImage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'project_id', 'image_id',
    ];

    /**
     * Attributes that are casted to another type.
     *
     * @var array
     */
    protected $casts = [
        'project_id' => 'integer',
        'image_id' => 'integer',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['image'];

    /**
     * Define relations.
     */
    public function project()
    {
       return $this->belongsTo('App\Models\Project');
    }

    public function image()
    {
        return $this->belongsTo('App\Models\Media', 'image_id');
    }
}
