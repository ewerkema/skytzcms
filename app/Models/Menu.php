<?php

namespace App\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * Get list of menu items with subpages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuWithSubpages()
    {
        return Cache::remember('menu-pages-with-subpages', 60, function () {
            return MenuItem::with('subItems')->get()->where('parent_id', null)->sortBy('order')->values();
        });
    }

    /**
     * Get list of menu items without subpages
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getMenuWithoutSubpages()
    {
        return Cache::remember('menu-pages-without-subpages', 60, function () {
            return MenuItem::all()->where('parent_id', null)->sortBy('order')->values();
        });
    }
}
