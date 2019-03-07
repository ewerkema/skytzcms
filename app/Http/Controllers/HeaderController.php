<?php

namespace App\Http\Controllers;

use App\Models\Header;
use Illuminate\Http\Request;

use App\Http\Requests;
use Validator;

class HeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $headers = Header::all();

        return response()->json($headers);
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
            'position' => 'required|integer',
            'image_id' => 'integer',
            'slider_id' => 'integer',
            'video' => 'url',
            'content' => '',
            'link_to_page' => 'integer',
            'link_to_url' => 'url',
            'open_in_new_tab' => 'required|boolean',
        ], [], [
            'name' => 'Naam',
            'position' => 'Positie',
            'image_id' => 'Afbeelding',
            'slider_id' => 'Slider',
            'video' => 'Youtube embed url',
            'content' => 'Tekst',
            'link_to_page' => 'Link naar pagina',
            'link_to_url' => 'Link naar URL',
            'open_in_new_tab' => 'Openen in nieuw tabblad',
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

        $header = Header::create($input);

        return response()->json($header, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $header = Header::find($id);

        return response()->json($header);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Header $header
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Header $header)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        if (isset($input['image_id']) && $input['image_id'] == 0)
            $input['image_id'] = null;

        if (!$header->update($input))
            return response()->json(['message' => 'Updaten van de header is niet gelukt.'], 500);

        return response()->json($header);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $header = Header::findOrFail($id);
        $header->delete();

        return response()->json(['message' => 'Header is succesvol verwijderd']);
    }
}
