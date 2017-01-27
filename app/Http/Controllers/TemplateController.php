<?php

namespace App\Http\Controllers;

use App\Models\Article;
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

        $article = $this->getArticle();

        if (!$page)
            abort(404);

        return View::make('templates.'.config('skytz.template').'.index')->with([
            'currentPage' => $page,
            'article' => $article,
        ]);
    }

    public function getArticle()
    {
        $article = false;
        if (isset($_GET['article'])) {
            $article = Article::whereTitle(str_slug($_GET['article']))->first();
        }

        return $article;
    }
}
