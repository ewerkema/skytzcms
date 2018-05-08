<?php

namespace App\Http\Controllers;

use App\Models\Social;
use Illuminate\Http\Request;
use Validator;

class SocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Social[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Social::all();
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array $data
     * @param $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id = 0)
    {
        return Validator::make($data, [
            'name' => (($id == 0) ? 'required' : 'nullable') . '|max:255',
            'type' => (($id == 0) ? 'nullable' : 'required') . '|in:'.(implode(',', array_keys(Social::TYPES))),
            'url' => (($id == 0) ? 'nullable' : 'required') . '|url',
        ], [], [
            'name' => 'Naam',
            'type' => 'Type',
            'url' => 'URL',
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

        $social = Social::create($input);

        return response()->json($social, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $social = Social::findOrFail($id);

        return response()->json($social);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Social $social
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Social $social)
    {
        $input = $request->all();
        $this->validator($input, $social->id)->validate();

        if (!$social->update($input))
            return response()->json(['message' => 'Updaten van het social media item niet gelukt'], 500);

        return response()->json($social);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Social::findOrFail($id)->delete();

        return response()->json(['message' => 'Social media item is succesvol verwijderd']);
    }
}
