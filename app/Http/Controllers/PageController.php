<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;
use Validator;
use Response;
use Input;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @param int $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data, $id = 0)
    {
        return Validator::make($data, [
            'slug' => 'required|alpha_dash|max:255|unique:pages,slug,'.$id.',id',
            'title' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_desc' => 'max:255',
            'menu' => 'required|boolean',
        ], [], [
            'slug' => 'Pagina link (URL)',
            'title' => 'Pagina naam',
            'meta_title' => 'Pagina titel',
            'meta_desc' => 'Pagina beschrijving',
            'menu' => 'Weergeven in menu',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  array  $data
     * @return Page
     */
    public function create(array $data)
    {
        return Page::create([
            'slug' => $data['slug'],
            'title' => $data['title'],
            'meta_title' => $data['meta_title'],
            'meta_desc' => $data['meta_desc'],
            'menu' => $data['menu'],
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

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Page $page
     * @return Response
     */
    public function update(Request $request, Page $page)
    {
        if($request->ajax()) {
            $input = $request->all();
            $this->validator($input, $page->id)->validate();

            if (!$page->update($input))
                return response()->json(['message' => 'Updaten van de pagina is niet gelukt.'], 500);

            session()->flash('flash_message', 'De pagina is succesvol aangepast');
            session()->flash('flash_title', 'Pagina aangepast');

            return response()->json($page, 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }
}
