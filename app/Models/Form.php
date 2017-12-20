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
     * Define relationships.
     */
    public function fields()
    {
        return $this->hasMany('App\Models\FormField');
    }
}
