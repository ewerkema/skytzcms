<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class CmsTemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($slug = 'index')
    {
        $page = Page::whereSlug($slug)->first();

        if (!$page)
            abort(404);

        return View::make('templates.admin.main')->with([
            'page' => $page,
            'menu' => Page::all()->where('menu', 1),
            'nonmenu' => Page::all()->where('menu', 0),
            'template' => 'templates.'.config('skytz.template').'.index'
        ]);
    }
}
