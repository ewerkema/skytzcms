<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class PageController extends Controller
{
    public function create()
    {
        $inputs = Input::all();
        $page = Page::create(array());
    }

    public function show($slug = 'index')
    {
        $page = Page::whereSlug($slug)->first();
        if (!$page)
            abort(404);
        return View::make('templates.'.config('skytz.template').'.index')->with('page', $page);
    }
}
