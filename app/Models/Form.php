<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'name', 'email', 'redirect', 'message', 'recaptcha',
    ];

    protected $casts = [
        'recaptcha' => 'boolean',
    ];

    /**
     * Returns the field name of the email field if there exists one.
     *
     * @return string
     */
    public function getEmailField()
    {
        $fields = $this->fields()->get();

        foreach ($fields as $field) {
            if ($field->type == 'email')
                return $field->formName();
        }

        return false;
    }

    /**
     * Define relationships.
     */
    public function fields()
    {
        return $this->hasMany('App\Models\FormField');
    }
}
