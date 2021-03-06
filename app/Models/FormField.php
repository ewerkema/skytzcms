<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type', 'name', 'options', 'required', 'placeholder', 'form_id', 'hidden_name',
    ];

    protected $casts = [
        'options' => 'array',
        'hidden_name' => 'boolean',
    ];

    /**
     * Returns the name attribute for form fields.
     *
     * @return string
     */
    public function formName()
    {
        return "form".$this->attributes['form_id']."-field".$this->attributes['id'];
    }

    /**
     * Define relationships.
     */
    public function form()
    {
        return $this->belongsTo('App\Models\Form');
    }
}
