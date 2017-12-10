<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Page;
use App\Http\Requests;
use App\Models\Project;
use Illuminate\Support\Facades\View;
use Session;

class TemplateController extends Controller
{

    public function __construct()
    {
        if (is_cms())
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
        $project = $this->getProject();

        if (!$page)
            abort(404);

        if (is_cms()) {
            return View::make('templates.admin.main')->with([
                'currentPage' => $page,
                'article' => $article,
                'project' => $project,
                'template' => 'templates.'.config('skytz.template').'.index',
            ]);
        }

        return View::make('templates.'.config('skytz.template').'.index')->with([
            'currentPage' => $page,
            'article' => $article,
            'project' => $project,
        ]);
    }

    public function getArticle()
    {
        $article = false;
        if (isset($_GET['article'])) {
            $article = Article::whereSlug(str_slug($_GET['article']));
        }

        return $article;
    }

    public function getProject()
    {
        $project = false;
        if (isset($_GET['project'])) {
            $project = Project::whereSlug(str_slug($_GET['project']));
        }

        return $project;
    }
}
