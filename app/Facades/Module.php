<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Module extends Facade
{
    protected static function getFacadeAccessor() { return 'Module'; }
}