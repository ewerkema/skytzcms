<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Response;
use App\Http\Requests;
use App\Models\Media;
use Input;
use File;
class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows = Media::paginate(8);
        return view('templates.admin.partials.medialist', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = Input::get('name');

        foreach($name as $filename) {
            $media = new Media;
            $media->name = $filename;
            $media->description = '';
            $media->extension = File::extension($filename);
            $media->mime = '';
            
            $media->save();
        }

        return Response::json(['status'=>'success','msg'=>'Afbeeldingen toegevoegd!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $media = Media::find($id);
        @unlink(public_path().'/images/'.$media->name);
        @unlink(public_path().'/images/large/'.$media->name);
        @unlink(public_path().'/images/thumbnail/'.$media->name);
        @unlink(public_path().'/docs/'.$media->name);
        $media->delete();
        return Response::json(['status' => 'success', 'msg' => 'Afbeelding verwijderd!']);
    }
}
