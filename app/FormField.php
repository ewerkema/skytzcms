<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type', 'name', 'options', 'required', 'placeholder', 'form_id',
    ];

    /**
     * Define relationships.
     */
    public function form()
    {
        return $this->belongsTo('Form');
    }
}
