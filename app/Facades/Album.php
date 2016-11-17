<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Album extends Facade
{
    protected static function getFacadeAccessor() { return 'Album'; }
}