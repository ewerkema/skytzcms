<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Page;
use Cache;
use Illuminate\Http\Request;
use Validator;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::withCount('menuItems')->get();

        return response()->json($pages);
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
        ], [], [
            'slug' => 'Pagina link (URL)',
            'title' => 'Pagina naam',
            'meta_title' => 'Pagina titel',
            'meta_desc' => 'Pagina beschrijving',
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

        $input['content'] = array();
        $input['published_content'] = array();
        $page = Page::create($input);

        if ($input['menu']) {
            MenuItem::create([
                'page_id' => $page->id,
                'parent_id' => array_key_exists('parent_id', $input) ? $input['parent_id'] : null,
                'order' => MenuItem::all()->max('order')+1,
            ]);
        }

        Cache::flush();

        if (array_key_exists('redirect', $input) && $input['redirect']) {
            session()->flash('flash_message', 'De pagina is succesvol aangemaakt');
            session()->flash('flash_title', 'Pagina aangemaakt');
        }

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

        if (!$page->update($input))
            return response()->json(['message' => 'Updaten van de pagina is niet gelukt.'], 500);

        if (request()->input('disableRedirect') !== null)
            return response()->json(['page' => $page]);

        Cache::flush();

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
            $page->publish();
        }

        return response()->json(['message' => 'Alle pagina\'s zijn gepubliceerd.']);

    }

    /**
     * Publish the selected page.
     *
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function publishPage(Page $page)
    {
        $page->publish();

        return response()->json(['message' => 'Pagina is succesvol gepubliceerd.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $page = Page::find($id);
        $menuItems = MenuItem::where('page_id', $id)->get();
        $menuItems->each(function ($menuItem) {
            $menuItem->subItems()->getResults()->each(function ($menuItem) {
                $menuItem->update(['parent_id' => null]);
            });

            $menuItem->delete();
        });
        $page->delete();

        session()->flash('flash_title', 'Pagina verwijderd');
        session()->flash('flash_message', "De pagina '$page->title' is succesvol verwijderd.");

        Cache::flush();

        $firstPage = Page::first();
        return response()->json(['redirectTo' => cms_url($firstPage->slug)]);
    }
}
