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
     * Get a validator for an incoming registration request.
     *
     * @param  array $data
     * @param int $id
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validatorOrder(array $data, $id = 0)
    {
        return Validator::make($data, [
            'order' => 'integer',
            'parent_id' => 'exists:pages,id',
        ], [], [
            'order' => 'Volgorde voor de pagina',
            'parent_id' => 'Pagina voor het submenu',
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

        if (!$input['header_image_id'])
            $input['header_image_id'] = NULL;

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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
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
                $updateContent[$key]['module'] = 0;
                $updateContent[$key]['module_id'] = 0;
            }
        }

        $page->content = $updateContent;

        if (!$page->save())
            return response()->json(['message' => 'Updaten van de inhoud is niet gelukt, neem aub contact met ons op.'], 500);

        return response()->json($page, 200);
    }

    /**
     * Update the the order and parent id in storage.
     *
     * @param  Request $request
     * @param  Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $input = $request->all();

        foreach ($input['pages'] as $order => $data) {
            if (isset($data['id'])) {
                $page = Page::find($data['id']);

                $page->order = $order;
                $page->parent_id = $data['parent_id'];

                if (!$page->save())
                    return response()->json(['message' => 'Updaten van de pagina volgorde is niet gelukt.'], 500);
            }
        }

        session()->flash('flash_message', 'De volgorde van de pagina\'s is succesvol aangepast');
        session()->flash('flash_title', 'Volgorde aangepast');

        return response()->json(['success' => 'Updaten van de volgorde is gelukt.']);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Page $page)
    {
        $input = $request->all();
        $this->validator($input, $page->id)->validate();

        if (!$input['header_image_id'])
            $input['header_image_id'] = NULL;

        if (!$page->update($input))
            return response()->json(['message' => 'Updaten van de pagina is niet gelukt.'], 500);

        session()->flash('flash_message', 'De pagina is succesvol aangepast');
        session()->flash('flash_title', 'Pagina aangepast');

        return response()->json(['page' => $page, 'redirectTo' => $page->getSlug()]);

    }

    /**
     * Publish all pages.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $page->delete();

        session()->flash('flash_title', 'Pagina verwijderd');
        session()->flash('flash_message', "De pagina '$page->title' is succesvol verwijderd.");

        $firstPage = Page::first();
        return response()->json(['redirectTo' => cms_url($firstPage->slug)]);
    }
}
