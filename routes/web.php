<?php

    Route::group(['namespace' => 'DavideCasiraghi\LaravelQuickMenus\Http\Controllers', 'middleware' => 'web'], function () {

        /* Menus */
        Route::resource('menus', 'MenuController');

        /* Menu Items */
        Route::get('/menuItems/index/{id}', 'MenuItemController@index')->name('menuItemsIndex');
        Route::resource('menuItems', 'MenuItemController');
        Route::put('/menuItem/updateOrder', 'MenuItemController@updateOrder')->name('menuItems.updateOrder');

        /* Menu Items Translations */
        Route::get('/menuItemTranslations/{menuItemId}/{languageCode}/{menuId}/create', 'MenuItemTranslationController@create');
        Route::get('/menuItemTranslations/{menuItemId}/{languageCode}/{menuId}/edit', 'MenuItemTranslationController@edit');
        Route::post('/menuItemTranslations/store', 'MenuItemTranslationController@store')->name('menuItemTranslations.store');
        Route::put('/menuItemTranslations/update', 'MenuItemTranslationController@update')->name('menuItemTranslations.update');
        Route::delete('/menuItemTranslations/destroy/{menuItemTranslationId}/{selectedMenuId}', 'MenuItemTranslationController@destroy')->name('menuItemTranslations.destroy');
    });
