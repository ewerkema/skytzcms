<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Http\Requests;
use Illuminate\Support\Facades\View;

class TemplateController extends Controller
{
    public function show($slug = 'index', $childSlug = false)
    {
        if ($childSlug) {
            $page = Page::whereSlug($slug)->first()->subpages()->whereSlug($childSlug)->first();
        } else {
            $page = Page::whereSlug($slug)->where('parent_id', NULL)->first();
        }

        if (!$page)
            abort(404);

        return View::make('templates.'.config('skytz.template').'.index')->with([
            'currentPage' => $page,
        ]);
    }
}
