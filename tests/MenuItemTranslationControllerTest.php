<?php

namespace DavideCasiraghi\LaravelQuickMenus\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use DavideCasiraghi\LaravelQuickMenus\Models\Menu;
use DavideCasiraghi\LaravelQuickMenus\Models\MenuItem;
use DavideCasiraghi\LaravelQuickMenus\Models\MenuItemTranslation;

class MenuItemTranslationControllerTest extends TestCase
{
    use WithFaker;

    /***************************************************************/

    /** @test */
    public function it_displays_the_menu_item_translation_create_page()
    {
        $this->authenticateAsAdmin();

        $menu = factory(Menu::class)->create();
        $menuItemId = 1;
        $languageCode = 'es';

        $this->get('/menuItemTranslations/'.$menuItemId.'/'.$languageCode.'/'.$menu->id.'/create')
            ->assertViewIs('laravel-quick-menus::menuItemTranslations.create')
            ->assertStatus(200);
    }

    /** @test */
    public function it_stores_a_valid_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $menu = factory(Menu::class)->create();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
            'menu_item_id' => 1,
            'selected_menu_id' => 1,
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', $data);

        $this->assertDatabaseHas('menu_item_translations', ['locale' => 'es', 'name' => 'Spanish menu item name']);
        $response->assertViewIs('laravel-quick-menus::menuItems.index');
    }

    /** @test */
    public function it_does_not_store_invalid_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $menu = factory(Menu::class)->create();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', []);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function it_displays_the_menu_item_translation_edit_page()
    {
        $this->authenticateAsAdmin();
        $menu = factory(Menu::class)->create();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
            'menu_item_id' => 1,
            'selected_menu_id' => 1,
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', $data);

        $response = $this->get('/menuItemTranslations/'.$menuItem->id.'/'.'es'.'/'.$menu->id.'/edit');
        $response->assertViewIs('laravel-quick-menus::menuItemTranslations.edit')
                 ->assertStatus(200);
    }

    /** @test */
    public function it_updates_valid_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $menu = factory(Menu::class)->create();
        $menuItem = factory(MenuItem::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
            'selected_menu_id' => 1,
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', $data);

        // Update the translation
        $attributes = ([
            'menu_item_translation_id' => 2,
            'name' => 'Spanish menu item name updated',
            'language_code' => 'es',
            'menu_item_id' => 1,
            'selected_menu_id' => 1,
          ]);
        $response = $this->followingRedirects()
                         ->put('/menuItemTranslations/update', $attributes);

        $response->assertViewIs('laravel-quick-menus::menuItems.index')
                 ->assertStatus(200);
        $this->assertDatabaseHas('menu_item_translations', ['locale' => 'es', 'name' => 'Spanish menu item name updated']);
    }

    /** @test */
    public function it_does_not_update_invalid_menu_item_translation_item()
    {
        $this->authenticateAsAdmin();
        $menu = factory(Menu::class)->create();
        $menuItem = factory(MenuItem::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
            'selected_menu_id' => 1,
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', $data);

        $response = $this->put('/menuItemTranslations/update', []);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function it_deletes_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $selectedMenuId = 1;
        $menu = factory(Menu::class)->create();
        $menuItem = factory(MenuItem::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
            'selected_menu_id' => $selectedMenuId,
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', $data);

        $response = $this->delete('/menuItemTranslations/destroy/2/'.$selectedMenuId);
        $response->assertRedirect('/menuItems/index/'.$selectedMenuId);
    }
}
