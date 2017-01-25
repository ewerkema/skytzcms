<?php

namespace App\Http\Controllers;

use App\Models\HtmlBlock;
use Illuminate\Http\Request;
use App\Http\Requests;
use Validator;

class HtmlBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function index()
    {
        return HtmlBlock::all();
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
            'name' => 'required|max:255',
        ], [], [
            'name' => 'Naam',
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

        $htmlBlock = HtmlBlock::create($input);

        return response()->json($htmlBlock, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $htmlBlock = HtmlBlock::findOrFail($id);

        return response()->json($htmlBlock);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HtmlBlock $htmlBlock)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        if (!$htmlBlock->update($input))
            return response()->json(['message' => 'Updaten van het html blok is niet gelukt.'], 500);

        return response()->json($htmlBlock);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $htmlBlock = HtmlBlock::findOrFail($id)->delete();

        return response()->json(['message' => 'Html blok is succesvol verwijderd']);
    }
}
