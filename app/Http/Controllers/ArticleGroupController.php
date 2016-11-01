<?php

namespace App\Http\Controllers;

use App\Models\ArticleGroup;
use Illuminate\Http\Request;
use Validator;
use Response;
use Input;

class ArticleGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return ArticleGroup::all();
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
            'title' => 'required|max:255',
        ], [], [
            'title' => 'Titel',
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

        $articleGroup = ArticleGroup::create($input);

        return response()->json($articleGroup, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $articleGroup = ArticleGroup::findOrFail($id);
        $articles = $articleGroup->articles()->get();

        if ($articles != null) {
            foreach ($articles as $article) {
                $article->delete();
            }
        }
        $articleGroup->delete();

        return response()->json(['message' => 'Nieuwsgroep is succesvol verwijderd']);
    }
}
