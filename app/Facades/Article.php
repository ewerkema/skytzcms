<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Article extends Facade
{
    protected static function getFacadeAccessor() { return 'Article'; }
}