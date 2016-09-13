<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'name', 'email', 'redirect', 'message',
    ];

    /**
     * Define relationships.
     */
    public function fields()
    {
        return $this->hasMany('FormField');
    }
}
