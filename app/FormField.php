<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'type', 'name', 'options', 'required', 'placeholder',
    ];

    public function setForm($form)
    {
        $this->attributes['form_id'] = $form->id;
        $this->save();
    }

    /**
     * Define relationships.
     */
    public function form()
    {
        return $this->belongsTo('Form');
    }
}
