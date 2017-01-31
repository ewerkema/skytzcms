<?php

namespace App\Http\Controllers;

use App\Models\Setting;
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
        if (!isset($_GET['page']))
            return Media::all();

        $rows = Media::paginate(8);
        return view('templates.admin.partials.medialist', compact('rows'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
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

        return Response::json(['status'=>'success','msg'=>'Media toegevoegd!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $media = Media::find($id);

        $this->checkGlobalHeader($media);
        $this->removePageHeaders($media);
        $media->albums()->detach();
        $media->sliders()->detach();
        $this->removeMedia($media);

        $media->delete();

        return Response::json(['status' => 'success', 'msg' => 'Media verwijderd!']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $media = Media::find($id);
        return response()->json($media);
    }

    /**
     * Remove global header reference is existing.
     *
     * @param $media
     */
    public function checkGlobalHeader($media)
    {
        $setting = Setting::where('name', 'header_image')->first();

        if ($setting->value == $media->id) {
            $setting->value = 0;
            $setting->save();
        }
    }

    /**
     * Remove page references to media.
     *
     * @param $media
     */
    public function removePageHeaders($media)
    {
        $media->pages()->getResults()->each(function ($page) {
            $page->header()->dissociate();
            $page->save();
        });
    }

    /**
     * Remove media from storage.
     *
     * @param $media
     */
    public function removeMedia($media)
    {
        @unlink(public_path().'/images/'.$media->name);
        @unlink(public_path().'/images/large/'.$media->name);
        @unlink(public_path().'/images/thumbnail/'.$media->name);
        @unlink(public_path().'/docs/'.$media->name);
    }
}
