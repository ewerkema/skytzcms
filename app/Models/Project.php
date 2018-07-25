<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'summary', 'body', 'address', 'published', 'project_group_id',
    ];

    /**
     * Attributes that are casted to another type.
     *
     * @var array
     */
    protected $casts = [
        'project_group_id' => 'integer',
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['images'];

    /**
     * Define relationships.
     */
    public function projectGroup()
    {
        return $this->belongsTo('App\Models\ProjectGroup');
    }

    public function images()
    {
        return $this->hasMany('App\Models\ProjectImage');
    }

    /**
     * Custom model functions.
     */
    public function notPublished()
    {
        return !$this->attributes['published'];
    }

    /**
     * Returns the published projects.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublished($builder)
    {
        return $builder->where('published', '=', true);
    }

    /**
     * Get slug of the current project.
     *
     */
    public function getSlug()
    {
        return str_slug($this->attributes['title']);
    }

    /**
     * Get full link to the project.
     */
    public function getLink()
    {
        return "?project=".$this->getSlug();
    }

    /**
     * Search projects and return where slug is equal.
     *
     */
    public static function whereSlug($slug)
    {
        $projects = Project::all();

        $findProject = null;
        foreach ($projects as $project) {
            if ($project->getSlug() == $slug) {
                $findProject = $project;
            }
        }

        return $findProject;
    }

    /**
     * Add images based on ID array input.
     */
    public function addImages($imageIds)
    {
        foreach ($imageIds as $image_id) {
            if ($image_id != 0) {
                ProjectImage::create([
                    'image_id' => $image_id,
                    'project_id' => $this->id,
                ]);
            }
        }
    }

    /**
     * Remove all images.
     */
    public function removeImages()
    {
        foreach ($this->images()->get() as $image) {
            $image->delete();
        }
    }
}
