<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Header extends Facade
{
    protected static function getFacadeAccessor() { return 'Header'; }
}