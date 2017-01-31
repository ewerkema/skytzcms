<?php

namespace App\Http\Controllers;

use App\Models\Article;
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
        if ($childSlug && Page::whereSlug($slug)->count()) {
            $page = Page::whereSlug($slug)->first()->subpages()->whereSlug($childSlug)->first();
        } else {
            $page = Page::whereSlug($slug)->where('parent_id', NULL)->first();
        }

        $article = $this->getArticle();

        if (!$page)
            abort(404);

        return View::make('templates.admin.main')->with([
            'currentPage' => $page,
            'article' => $article,
            'template' => 'templates.'.config('skytz.template').'.index'
        ]);
    }


    public function getArticle()
    {
        $article = false;
        if (isset($_GET['article'])) {
            $article = Article::whereSlug($_GET['article']);
        }

        return $article;
    }
}
