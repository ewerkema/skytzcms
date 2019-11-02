<?php

namespace App\Http\Controllers;

use App\Facades\Menu;
use App\Models\MenuItem;
use Cache;
use Illuminate\Http\Request;
use Validator;

class MenuController extends Controller
{
    public function index()
    {
        $menu = Menu::getMenuWithSubpages();

        return response()->json(['menu' => $menu]);
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
            'parent_id' => 'exists:menu_items,id',
        ], [], [
            'order' => 'Volgorde voor de pagina',
            'parent_id' => 'Pagina voor het submenu',
        ]);
    }

    /**
     * Update the the order and parent id in storage.
     *
     * @param  Request $request
     * @param \App\Models\Page $page
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateOrder(Request $request)
    {
        $input = $request->all();

        foreach ($input['pages'] as $order => $data) {
            if (isset($data['id'])) {
                $menuItem = MenuItem::find($data['id']);

                $menuItem->order = $order;
                $menuItem->parent_id = $data['parent_id'];

                if (!$menuItem->save())
                    return response()->json(['message' => 'Updaten van de menu volgorde is niet gelukt.'], 500);
            }
        }

        Cache::flush();

        return response()->json(['success' => 'Updaten van de volgorde is gelukt.']);
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
            'title' => 'required_without:page_id|max:255',
            'link' => 'required_without:page_id|max:255',
            'page_id' => 'required_without:link|nullable|sometimes|exists:pages,id',
            'open_in_new_tab' => 'required|boolean',
        ], [], [
            'title' => 'Link naam',
            'link' => 'Losse link',
            'page_id' => 'Pagina',
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
        $input = $this->processMenuItem($input);

        $this->validator($input)->validate();

        $input['order'] = MenuItem::all()->max('order')+1;
        $menuItem = MenuItem::create($input);

        Cache::flush();

        return response()->json($menuItem, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $input = $request->all();
        $input = $this->processMenuItem($input);
        \Log::info(print_r($input, true));

        $this->validator($input)->validate();

        if (!$menuItem->update($input))
            return response()->json(['message' => 'Updaten van het menu item is niet gelukt.'], 500);

        Cache::flush();

        return response()->json($menuItem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->subItems->each(function (MenuItem $menuItem) {
            $menuItem->delete();
        });

        $menuItem->delete();

        Cache::flush();

        return response()->json(['message' => 'Menu item is succesvol verwijderd']);
    }

    /**
     * Process menu item input.
     *
     * @param array $input
     * @return array
     */
    private function processMenuItem(array $input)
    {
        if (isset($input['page_id']) && $input['page_id'] == 0) {
            $input['page_id'] = null;
        }

        if (!is_numeric($input['page_id'])) {
            $input['page_id'] = null;
        }

        return $input;
    }
}
