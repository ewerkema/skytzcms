<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;

class SliderMediaController extends Controller
{
    /**
     * Display a listing of the resource relation.
     *
     * @param $sliderId
     * @return \Illuminate\Http\Response
     */
    public function index($sliderId)
    {
        return Slider::findOrFail($sliderId)->media()->get();
    }

    /**
     * Create a new relation between resources. Argument $request
     * can contain a single media ID or a list of media IDs.
     *
     * @param Request $request
     * @param $sliderId
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $sliderId)
    {
        $input = $request->all();
        if (!isset($input['media']))
            return response()->json(['message' => 'Er is geen media opgestuurd om aan de slider te koppelen']);

        $slider = Slider::findOrFail($sliderId);
        $slider->media()->attach($input['media']);
        return response()->json(['message' => 'Afbeelding(en) is/zijn succesvol aan de slider toegevoegd']);
    }

    /**
     * Display the specified resource.
     *
     * @param $sliderId
     * @param $mediaId
     * @return \Illuminate\Http\Response=
     */
    public function show($sliderId, $mediaId)
    {
        $slider = Slider::findOrFail($sliderId);
        return $slider->media()->where('id', '=', $mediaId)->get();
    }

    /**
     * Remove the specified resource relation from storage.
     *
     * @param $sliderId
     * @param $mediaId
     * @return \Illuminate\Http\Response
     */
    public function destroy($sliderId, $mediaId)
    {
        $slider = Slider::findOrFail($sliderId);
        $slider->media()->detach($mediaId);
        return response()->json(['message' => 'Afbeelding is succesvol uit de slider verwijderd']);
    }

}
