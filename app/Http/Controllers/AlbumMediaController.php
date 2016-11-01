<?php

namespace App\Http\Controllers;

use App\Models\Album;
use Illuminate\Http\Request;

class AlbumMediaController extends Controller
{
    /**
     * Display a listing of the resource relation.
     *
     * @param $albumId
     * @return \Illuminate\Http\Response
     */
    public function index($albumId)
    {
        return Album::findOrFail($albumId)->media()->get();
    }

    /**
     * Create a new relation between resources. Argument $request
     * can contain a single media ID or a list of media IDs.
     *
     * @param Request $request
     * @param $albumId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $albumId)
    {
        $input = $request->all();
        if (!isset($input['media']))
            return response()->json(['message' => 'Er is geen media opgestuurd om aan het album te koppelen']);

        $album = Album::findOrFail($albumId);
        $album->media()->attach($input['media']);
        return response()->json(['message' => 'Afbeelding(en) is/zijn succesvol aan het album toegevoegd']);
    }

    /**
     * Display the specified resource.
     *
     * @param $albumId
     * @param $mediaId
     * @return \Illuminate\Http\Response=
     */
    public function show($albumId, $mediaId)
    {
        $album = Album::findOrFail($albumId);
        return $album->media()->where('id', '=', $mediaId)->get();
    }

    /**
     * Remove the specified resource relation from storage.
     *
     * @param $albumId
     * @param $mediaId
     * @return \Illuminate\Http\Response
     */
    public function destroy($albumId, $mediaId)
    {
        $album = Album::findOrFail($albumId);
        $album->media()->detach($mediaId);
        return response()->json(['message' => 'Afbeelding is succesvol uit het album verwijderd']);
    }

}
