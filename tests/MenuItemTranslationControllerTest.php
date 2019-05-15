<?php

namespace DavideCasiraghi\LaravelQuickMenus\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use DavideCasiraghi\LaravelQuickMenus\Models\MenuItemTranslation;

class MenuItemTranslationControllerTest extends TestCase
{
    use WithFaker;

    /***************************************************************/

    /** @test */
    public function it_displays_the_menu_item_translation_create_page()
    {
        $this->authenticateAsAdmin();

        $menuItemId = 1;
        $languageCode = 'es';

        $this->get('/menuItemTranslations/'.$menuItemId.'/'.$languageCode.'/create')
            ->assertViewIs('laravel-events-calendar::menuItemTranslations.create')
            ->assertStatus(200);
    }

    /** @test */
    public function it_stores_a_valid_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', $data);

        $this->assertDatabaseHas('menu_item_translations', ['locale' => 'es', 'name' => 'Spanish menu item name']);
        $response->assertViewIs('laravel-events-calendar::eventCategories.index');
    }

    /** @test */
    public function it_does_not_store_invalid_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $response = $this
            ->followingRedirects()
            ->post('/menuItemTranslations/store', []);

        $response->assertSessionHasErrors();
    }

    /** @test */
    public function it_displays_the_menu_item_translation_edit_page()
    {
        $this->authenticateAsAdmin();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
        ];

        $this->post('/menuItemTranslations/store', $data);

        $response = $this->get('/menuItemTranslations/'.$menuItem->id.'/'.'es'.'/edit');
        $response->assertViewIs('laravel-events-calendar::menuItemTranslations.edit')
                 ->assertStatus(200);
    }

    /** @test */
    public function it_updates_valid_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
        ];

        $this->post('/menuItemTranslations/store', $data);

        // Update the translation
        $attributes = ([
            'menu_item_translation_id' => 2,
            'language_code' => 'es',
            'name' => 'Spanish menu item name updated',
          ]);
        $response = $this->followingRedirects()
                         ->put('/menuItemTranslations/update', $attributes);
        $response->assertViewIs('laravel-events-calendar::eventCategories.index')
                 ->assertStatus(200);
        $this->assertDatabaseHas('menu_item_translations', ['locale' => 'es', 'name' => 'Spanish menu item name updated']);

        // Update with no attributes - to not pass validation
        //$response = $this->followingRedirects()
                        // ->put('/menuItemTranslations/update', [])->dump();
                        // ->assertSessionHasErrors();
    }

    /** @test */
    public function it_does_not_update_invalid_menu_item()
    {
        $this->authenticateAsAdmin();
        $menuItem = factory(MenuItemTranslation::class)->create([
                            'name' => 'Regular Jams',
                            'slug' => 'regular-jams',
                        ]);

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
        ];

        $this->post('/menuItemTranslations/store', $data);

        // Update the translation
        $attributes = ([
            'menu_item_translation_id' => 2,
            'language_code' => 'es',
            'name' => '',
          ]);
        $response = $this->followingRedirects()
                         ->put('/menuItemTranslations/update', $attributes);
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function it_deletes_menu_item_translation()
    {
        $this->authenticateAsAdmin();
        $menuItem = factory(MenuItemTranslation::class)->create();

        $data = [
            'menu_item_id' => $menuItem->id,
            'language_code' => 'es',
            'name' => 'Spanish menu item name',
        ];

        $this->post('/menuItemTranslations/store', $data);

        $response = $this->delete('/menuItemTranslations/destroy/2');
        $response->assertRedirect('/eventCategories');
    }
}