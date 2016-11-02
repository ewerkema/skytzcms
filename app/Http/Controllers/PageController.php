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
            'parent_id' => 'exists:pages,id',
        ], [], [
            'slug' => 'Pagina link (URL)',
            'title' => 'Pagina naam',
            'meta_title' => 'Pagina titel',
            'meta_desc' => 'Pagina beschrijving',
            'menu' => 'Weergeven in menu',
            'parent_id' => 'Pagina voor het submenu',
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
        $input = $request->all();
        $this->validator($input)->validate();

        $input['content'] = array();
        $input['published_content'] = array();
        $page = Page::create($input);

        session()->flash('flash_message', 'De pagina is succesvol aangemaakt');
        session()->flash('flash_title', 'Pagina aangemaakt');

        return response()->json(['page' => $page, 'redirectTo' => $page->getSlug()], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $page = Page::find($id);
        return response()->json($page);
    }

    /**
     * Display the specified resources content.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function grid($page)
    {
        $page = Page::find($page);
        return response()->json($page->content);
    }

    /**
     * Save the specified resources content.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function updateGrid(Request $request, Page $page)
    {
        $input = $request->all();

        if (!isset($input['content']))
            return response()->json(['message' => 'Updaten van de indeling is niet gelukt, neem aub contact met ons op.'], 500);

        $updateContent = array();
        foreach ($input['content'] as $name => $block) {
            $key = array_search($name, array_column($page->content, 'name'));

            $block['name'] = $name;
            $block['content'] = ($key !== false) ? $page->content[$key]['content'] : "";
            $updateContent[] = $block;
        }
        $page->content = $updateContent;

        if (!$page->save())
            return response()->json(['message' => 'Updaten van de indeling is niet gelukt, neem aub contact met ons op.'], 500);

        return response()->json(['message' => 'Pagina indeling succesvol opgeslagen!'], 200);

    }

    /**
     * Display the specified resources backend content.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function content($page)
    {
        $page = Page::find($page);
        return response()->json($page->getContent());
    }

    /**
     * Save the updated blocks to the page.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\Response
     */
    public function updateContent(Request $request, Page $page)
    {
        $input = $request->all();

        if (!isset($input['content']))
            return response()->json(['message' => 'Updaten van de inhoud is niet gelukt, neem aub contact met ons op.'], 500);

        $updateContent = $page->content;
        foreach ($input['content'] as $name => $block) {
            $key = array_search($name, array_column($updateContent, 'name'));
            $updateContent[$key]['content'] = $block;
            if (!isset($updateContent[$key]['x'])) {
                $updateContent[$key]['name'] = "block0";
                $updateContent[$key]['x'] = 0;
                $updateContent[$key]['y'] = 0;
                $updateContent[$key]['width'] = 12;
                $updateContent[$key]['height'] = 1;
            }
        }

        $page->content = $updateContent;

        if (!$page->save())
            return response()->json(['message' => 'Updaten van de inhoud is niet gelukt, neem aub contact met ons op.'], 500);

        return response()->json($page, 200);
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
        $input = $request->all();
        $this->validator($input, $page->id)->validate();

        if (!$page->update($input))
            return response()->json(['message' => 'Updaten van de pagina is niet gelukt.'], 500);

        session()->flash('flash_message', 'De pagina is succesvol aangepast');
        session()->flash('flash_title', 'Pagina aangepast');

        return response()->json(['page' => $page, 'redirectTo' => $page->getSlug()]);

    }

    /**
     * Publish all pages.
     *
     * @return Response
     */
    public function publish()
    {
        $pages = Page::all();
        foreach ($pages as $page) {
            $page->published_content = $page->content;
            $page->save();
        }

        return response()->json(['message' => 'Alle pagina\'s zijn gepubliceerd.']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();
    }
}
