<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Validator;
use Response;
use Input;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Article::latest()->get();
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'article_group_id' => 'required|exists:article_groups,id',
            'title' => 'required|max:255',
            'body' => 'required',
            'published' => 'required|boolean',
        ], [], [
            'article_group_id' => 'Nieuwsgroep',
            'title' => 'Titel',
            'summary' => 'Introductie voor het artikel',
            'body' => 'Artikel',
            'published' => 'Gepubliceerd',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        if (isset($input['image_id']) && $input['image_id'] == 0)
            $input['image_id'] = null;

        $article = Article::create($input);

        return response()->json($article, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::findOrFail($id);

        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        if (isset($input['image_id']) && $input['image_id'] == 0)
            $input['image_id'] = null;

        if (!$article->update($input))
            return response()->json(['message' => 'Updaten van het artikel is niet gelukt.'], 500);

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id)->delete();

        return response()->json(['message' => 'Artikel is succesvol verwijderd']);
    }
}
