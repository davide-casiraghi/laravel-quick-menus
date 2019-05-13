<?php

namespace DavideCasiraghi\LaravelQuickMenus\Http\Controllers;

use Route;
use DavideCasiraghi\LaravelQuickMenus\Models\Menu;
use Validator;
use DavideCasiraghi\LaravelQuickMenus\Models\MenuItem;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class MenuItemController extends Controller
{
    /* Restrict the access to this resource just to logged in users, except some */
    public function __construct()
    {
        $this->middleware('admin');
    }

    /***************************************************************************/

    /**
     * Display a listing of the resource.
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $countriesAvailableForTranslations = LaravelLocalization::getSupportedLocales();

        $selectedMenuName = Menu::find($id)->name;
        $menuItemsTree = MenuItem::getItemsTree($id);

        return view('menuItems.index', compact('menuItemsTree'))
                    ->with('selectedMenuId', $id)
                    ->with('selectedMenuName', $selectedMenuName)
                    ->with('countriesAvailableForTranslations', $countriesAvailableForTranslations);
    }

    /***************************************************************************/

    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //dd($request->menuId);
        $menu = Menu::orderBy('name')->pluck('name', 'id');
        $menuItems = MenuItem::orderBy('name')->pluck('name', 'id');
        $menuItemsTree = MenuItem::getItemsTree(0);
        $routeNames = array_map(function (\Illuminate\Routing\Route $route) {
            if (isset($route->action['as'])) {
                return $route->action['as'];
            }
        }, (array) Route::getRoutes()->getIterator());

        // Set the default language to edit the post for the admin to English (to avoid bug with null name)
        //App::setLocale('en');

        return view('menuItems.create')
            ->with('menuItems', $menuItems)
            ->with('menu', $menu)
            ->with('menuItemsTree', $menuItemsTree)
            ->with('routeNames', $routeNames)
            ->with('selectedMenuId', $request->menuId);
    }

    /***************************************************************************/

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required',
            ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Set the default language to edit the post for the admin to English (to avoid bug with null name)
        App::setLocale('en');

        $menuItem = new MenuItem();
        $this->saveOnDb($request, $menuItem);
        //dd($request->menu_id);

        return redirect()->route('menuItemsIndex', ['id' => $request->menu_id])
                        ->with('success', __('messages.menu_item_added_successfully'));
    }

    /***************************************************************************/

    /**
     * Display the specified resource.
     *
     * @param  \DavideCasiraghi\LaravelQuickMenus\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function show(MenuItem $menuItem)
    {
        return view('menuItems.show', compact('menuItem'));
    }

    /***************************************************************************/

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \DavideCasiraghi\LaravelQuickMenus\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function edit(MenuItem $menuItem)
    {
        $menu = Menu::orderBy('name')->pluck('name', 'id');
        $menuItems = MenuItem::orderBy('name')->pluck('name', 'id');
        $menuItemsSameMenuAndLevel = $this->getItemsSameMenuAndLevel($menuItem->menu_id, $menuItem->parent_item_id, 1);
        $menuItemsTree = MenuItem::getItemsTree($menuItem->menu_id);
        $routeNames = array_map(function (\Illuminate\Routing\Route $route) {
            if (isset($route->action['as'])) {
                return $route->action['as'];
            }
        }, (array) Route::getRoutes()->getIterator());

        // Set the default language to edit the post for the admin to English (to avoid bug with null name)
        //App::setLocale('en');

        return view('menuItems.edit', compact('menuItem'))
                    ->with('menuItems', $menuItems)
                    ->with('menuItemsSameMenuAndLevel', $menuItemsSameMenuAndLevel)
                    ->with('menuItemsTree', $menuItemsTree)
                    ->with('menu', $menu)
                    ->with('routeNames', $routeNames);
    }

    /***************************************************************************/

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \DavideCasiraghi\LaravelQuickMenus\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        request()->validate([
            'name' => 'required',
        ]);

        // Set the default language to edit the post for the admin to English (to avoid bug with null name)
        App::setLocale('en');

        $this->saveOnDb($request, $menuItem);

        return redirect()->route('menuItemsIndex', ['id' => $request->menu_id])
                        ->with('success', __('messages.menu_item_updated_successfully'));
    }

    /***************************************************************************/

    /**
     * Remove the specified resource from storage.
     *
     * @param  \DavideCasiraghi\LaravelQuickMenus\Models\MenuItem  $menuItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(MenuItem $menuItem)
    {
        $menuItem->delete();

        return redirect()->route('menuItemsIndex', ['id' => $menuItem->menu_id])
                        ->with('success', __('messages.menu_item_deleted_successfully'));
    }

    /***************************************************************************/

    /**
     * Save/Update the record on DB.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \DavideCasiraghi\LaravelQuickMenus\Models\MenuItem
     * @return string $ret - the ordinal indicator (st, nd, rd, th)
     */
    public function saveOnDb($request, $menuItem)
    {
        $menuItem->translateOrNew('en')->name = $request->get('name');
        $menuItem->translateOrNew('en')->compact_name = Str::slug($request->get('name'), '-');
        if (! $request->get('parent_item_id')) {
            $menuItem->parent_item_id = 0;
        } else {
            $menuItem->parent_item_id = $request->get('parent_item_id');
        }

        $menuItem->url = $request->get('url');
        $menuItem->font_awesome_class = $request->get('font_awesome_class');
        $menuItem->hide_name = ($request->hide_name == 'on') ? 1 : 0;
        $menuItem->route = $request->get('route');
        $menuItem->type = $request->get('type');
        $menuItem->menu_id = $request->get('menu_id');
        $menuItem->access = $request->get('access');

        if ($request->get('order')) {
            if ($request->get('order') != $menuItem->id) {
                $this->updateOrder($menuItem->menu_id, $menuItem->parent_item_id, $menuItem->id, $request->get('order'));
            }
        }

        $menuItem->save();
    }

    /***************************************************************************/

    /**
     * Update the menu items order on DB.
     *
     * @param int $menuId - the menu id
     * @param int  $parentItemId - the parent item id (update the order just of the elements on this level)
     * @param int $itemId - the id of the element that has been saved
     * @param string $position - (first, last or the id of the menu item we want to place this one after)
     * @return void
     */
    public function updateOrder($menuId, $parentItemId, $itemId, $position)
    {
        $menuItemsSameMenuAndLevel = $this->getItemsSameMenuAndLevel($menuId, $parentItemId, 0);
        $menuItem = new MenuItem();

        switch ($position) {
            case 'first':
                $i = 2;
                foreach ($menuItemsSameMenuAndLevel as $key => $item) {
                    if ($item->id == $itemId) {
                        $item->order = 1;
                    } else {
                        $item->order = $i;
                        $i++;
                    }
                    $item->save();
                }
                break;

            case 'last':
                $i = 1;
                $lastIndex = count($menuItemsSameMenuAndLevel);
                foreach ($menuItemsSameMenuAndLevel as $key => $item) {
                    if ($item->id == $itemId) {
                        $item->order = $lastIndex;
                    } else {
                        $item->order = $i;
                        $i++;
                    }
                    $item->save();
                }
                break;

            default:
                $i = 1;
                $afterThisElementIndex = 0;
                foreach ($menuItemsSameMenuAndLevel as $key => $item) {
                    if ($item->id == $itemId) {
                        $menuItem = $item; // store this element for later
                    } elseif ($item->id == $position) {  // we wil place the elemenet after this one
                        $item->order = $i;
                        $afterThisElementIndex = $i;
                        $i = $i + 2;
                    } else { // all the other elements
                        $item->order = $i;
                        $i++;
                    }
                    $item->save();
                }
                // assign the order after the specified menu item and save
                    $menuItem->order = $afterThisElementIndex + 1;
                    $menuItem->save();

                break;
        }
    }

    /***************************************************************************/

    /**
     * Get the items of the same menu and level.
     *
     * @param int $menuId - the menu id
     * @param  int $parentItemId - the parent menu item id
     * @param  int $kind 1 (retun the pluck) - 0 (return the items)
     * @return array
     */
    public function getItemsSameMenuAndLevel($menuId, $parentItemId, $kind)
    {
        if ($kind == 1) {
            $ret = MenuItem::where('parent_item_id', '=', $parentItemId)
                                            ->where('menu_id', '=', $menuId)
                                            ->orderBy('order')
                                            ->pluck('name', 'id');
        } else {
            $ret = MenuItem::where('parent_item_id', '=', $parentItemId)
                                            ->where('menu_id', '=', $menuId)
                                            ->orderBy('order')
                                            ->get();
        }

        return $ret;
    }
}