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
        return $this->cache('menu-pages-with-subpages', function () {
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
        return $this->cache('menu-pages-without-subpages', function () {
            return MenuItem::all()->where('parent_id', null)->sortBy('order')->values();
        });
    }

    /**
     * Get list of menu items (pages and subpages).
     *
     * @return mixed
     */
    public function menuPages()
    {
        return $this->cache('non-menu-pages', function() {
            return MenuItem::all()->values();
        });
    }

    /**
     * Cache the menu items.
     *
     * @param $name
     * @param $function
     * @return mixed
     */
    private function cache($name, $function)
    {
        if (config('app.env') !== 'production') {
            return $function();
        }

        return Cache::remember($name, 60, function () use ($function) {
            return $function();
        });
    }
}
