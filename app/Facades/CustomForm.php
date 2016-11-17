<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class CustomForm extends Facade
{
    protected static function getFacadeAccessor() { return 'Form'; }
}