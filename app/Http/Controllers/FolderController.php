<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use Illuminate\Http\Request;
use Response;
use Validator;

class FolderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $folders = Folder::all();

        return Response::json($folders);
    }

    /**
     * Get a validator for an incoming request.
     *
     * @param  array $data
     * @param int $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id = 0)
    {
        return Validator::make($data, [
            'name' => 'required|unique:folders,name,'.$id,
        ], [], [
            'name' => 'Naam',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();

        $folder = Folder::create([
            'name' => $request->get('name'),
        ]);

        return Response::json($folder);
    }

    /**
     * Display the specified resource.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Folder $folder)
    {
        return Response::json($folder);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param Folder $folder
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Folder $folder)
    {
        $input = $request->all();
        $this->validator($input, $folder->id)->validate();

        $folder->update([
            'name' => $request->get('name')
        ]);

        return Response::json($folder);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Folder $folder
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Folder $folder)
    {
        $folder->media->each(function($media) {
            $media->folder()->dissociate()->save();
        });
        $folder->delete();

        return Response::json(['status' => 'success', 'message' => 'Successfully deleted the folder and the media contained in the folder.']);
    }
}
