<?php

namespace DavideCasiraghi\LaravelQuickMenus\Http\Controllers;

use DavideCasiraghi\LaravelQuickMenus\Models\MenuItemTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Validator;

class MenuItemTranslationController extends Controller
{
    /* Restrict the access to this resource just to logged in users */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * Show the form for creating a new resource.
     * @param int $menuItemId
     * @param string $languageCode
     * @param int $menuId
     * @return \Illuminate\Http\Response
     */
    public function create($menuItemId, $languageCode, $menuId)
    {
        $selectedLocaleName = $this->getSelectedLocaleName($languageCode);

        return view('laravel-quick-menus::menuItemTranslations.create')
                ->with('menuItemId', $menuItemId)
                ->with('selectedMenuId', $menuId)
                ->with('languageCode', $languageCode)
                ->with('selectedLocaleName', $selectedLocaleName);
    }

    // **********************************************************************

    /**
     * Show the form for editing the specified resource.
     * @param int $menuItemId
     * @param string $languageCode
     * @param int $menuId
     * @return \Illuminate\Http\Response
     */
    public function edit($menuItemId, $languageCode, $menuId)
    {
        $menuItemTranslation = MenuItemTranslation::where('menu_item_id', $menuItemId)
                        ->where('locale', $languageCode)
                        ->first();

        $selectedLocaleName = $this->getSelectedLocaleName($languageCode);

        return view('laravel-quick-menus::menuItemTranslations.edit', compact('menuItemTranslation'))
                    ->with('menuItemId', $menuItemId)
                    ->with('selectedMenuId', $menuId)
                    ->with('languageCode', $languageCode)
                    ->with('selectedLocaleName', $selectedLocaleName);
    }

    // **********************************************************************

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validate form datas
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $menuItemTranslation = new MenuItemTranslation();
        $menuItemTranslation->menu_item_id = $request->get('menu_item_id');
        $menuItemTranslation->locale = $request->get('language_code');

        $menuItemTranslation->name = $request->get('name');
        $menuItemTranslation->slug = Str::slug($menuItemTranslation->name, '-');

        $menuItemTranslation->save();

        $selectedMenuId = $request->get('selected_menu_id');

        return redirect()->route('menuItemsIndex', ['id' => $selectedMenuId])
                        ->with('success', 'Translation created successfully.');
    }

    // **********************************************************************

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'name' => 'required',
        ]);

        //$menuItemTranslation = MenuItemTranslation::where('id', $request->get('menu_item_translation_id'));
        $menuItemTranslation = MenuItemTranslation::find($request->get('menu_item_translation_id'));
        //dd($menuItemTranslation);

        //dd($menuItemTranslation->id);
        $mi_t = [];
        $mi_t['name'] = $request->get('name');
        $mi_t['slug'] = Str::slug($request->get('name'), '-');

        $menuItemTranslation->update($mi_t);

        $selectedMenuId = $request->get('selected_menu_id');

        return redirect()->route('menuItemsIndex', ['id' => $selectedMenuId])
                        ->with('success', 'Translation updated successfully');
    }

    // **********************************************************************

    /**
     * Remove the specified resource from storage.
     * @param int $menuItemTranslationId
     * @param int $selectedMenuId
     * @return \Illuminate\Http\Response
     */
    public function destroy($menuItemTranslationId, $selectedMenuId)
    {
        $menuItemTranslation = MenuItemTranslation::find($menuItemTranslationId);
        $menuItemTranslation->delete();

        return redirect()->route('menuItemsIndex', ['id' => $selectedMenuId])
                        ->with('success', 'Translation deleted successfully');
    }
}
