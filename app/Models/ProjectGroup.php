<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];

    /**
     * Define relationships.
     */
    public function projects()
    {
        return $this->hasMany('App\Models\Project', 'project_group_id')->orderBy('created_at', 'DESC');
    }

}
