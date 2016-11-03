<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Setting;
use App\Http\Requests;
use Illuminate\Support\Facades\View;
use DB;

class CmsTemplateController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function show($slug = 'index', $childSlug = false)
    {
        if ($childSlug) {
            $page = Page::whereSlug($slug)->first()->subpages()->whereSlug($childSlug)->first();
        } else {
            $page = Page::whereSlug($slug)->where('parent_id', NULL)->first();
        }

        if (!$page)
            abort(404);

        return View::make('templates.admin.main')->with([
            'currentPage' => $page,
            'template' => 'templates.'.config('skytz.template').'.index'
        ]);
    }
}
