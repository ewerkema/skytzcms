<?php

namespace App\Http\Controllers;

use App\Models\Header;
use App\Models\Setting;
use Illuminate\Http\Request;
use Image;
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
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function index()
    {
        if (!isset($_GET['page'])) {
            $media = Input::get('filterFolder') ? Media::withoutFolder()->get() : Media::all();

            return Response::json($media);
        }

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
            if (!$this->createMedia($filename)) {
                return Response::json(['status' => 'error', 'message' => "De afbeelding $filename bestaat al!"]);
            }
        }

        return Response::json(['status'=>'success','msg'=>'Media toegevoegd!']);
    }

    /**
     * Create media from filename.
     *
     * @param $filename
     * @return Media|bool
     */
    public function createMedia($filename)
    {
        if ($this->mediaExists($filename))
            return false;

        $media = new Media;
        $media->name = $filename;
        $media->description = '';
        $media->extension = File::extension($filename);
        $media->mime = '';

        $media->save();

        return $media;
    }

    /**
     * Returns whether the filename already exists in database.
     *
     * @param string $filename
     * @return bool
     */
    public function mediaExists($filename)
    {
        if (File::extension($filename)!='docx' && File::extension($filename)!='pdf' && File::extension($filename)!='doc')
            $path = 'images/'.$filename;
        else
            $path = 'docs'.$filename;

        return Media::where('path', $path)->exists();
    }

    /**
     * Create header from coordinates
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createHeader($id, Request $request)
    {
        $media = Media::find($id);
        $coordinates = $request;

        $headerExtension = 1;
        $headerPath = str_replace(".".$media->extension, "", $media->path);
        while (file_exists($headerPath . "header" . $headerExtension . "." . $media->extension)) {
            $headerExtension++;
        }

        $headerPath = $headerPath . "header" . $headerExtension . "." . $media->extension;

        Image::make($media->path)->crop(
            round($coordinates['w']),
            round($coordinates['h']),
            round($coordinates['x']),
            round($coordinates['y'])
        )->save(str_replace("images/", "tmp/", $headerPath));

        $header = $this->createMedia($headerPath);

        return Response::json($header);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->destroyMedia($id);

        return Response::json(['status' => 'success', 'msg' => 'Media verwijderd!']);
    }

    public function destroyMany(Request $request)
    {
        $images = $request->get('images');

        if ($images) {
            foreach ($images as $image) {
                $this->destroyMedia($image);
            }
        }

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
        $setting = Setting::where('name', 'header_id')->first();

        if (!$setting->value)
            return;

        $header = Header::find($setting->value);

        if ($header->image_id == $media->id) {
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

    private function destroyMedia($id)
    {
        $media = Media::find($id);

        $this->checkGlobalHeader($media);
        $this->removePageHeaders($media);
        $media->albums()->detach();
        $media->sliders()->detach();
        $this->removeMedia($media);
        $media->delete();
    }
}
