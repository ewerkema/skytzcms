<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Media;
use Illuminate\Http\Request;
use Response;
use Validator;

class FolderMediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Folder $folder)
    {
        $media = $folder->media()->get();

        return Response::json($media);
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
            'media_id' => 'required|exists:media,id',
        ], [], [
            'media_id' => 'Media',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Folder $folder
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Folder $folder, Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $media = Media::find($request->get('media_id'));
        $folder->media()->save($media);

        return Response::json(['message' => 'Afbeelding is succesvol aan folder toegevoegd']);
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
     * @param Folder $folder
     * @param Media $media
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Folder $folder, $mediaId)
    {
        $media = Media::find($mediaId);
        $media->folder_id = null;
        $media->save();

        return Response::json(['message' => 'Afbeelding is succesvol uit de folder verwijderd']);
    }
}
