<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'value',
    ];

    /**
     * Custom getter for only the key value pairs
     *
     * @param string $name
     * @return mixed
     */
    public function get($name)
    {
        return $this->where('name', $name)->first()->value;
    }
}
