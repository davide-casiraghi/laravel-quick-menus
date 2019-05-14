<?php

namespace DavideCasiraghi\LaravelQuickMenus\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\WithFaker;
use DavideCasiraghi\LaravelQuickMenus\Models\MenuItem;

class MenuItemControllerTest extends TestCase
{
    use WithFaker;

    /***************************************************************/

    /** @test */
    public function it_runs_the_test_factory()
    {
        $menuItem = factory(MenuItem::class)->create([
                            'name' => 'Main menu',
                        ]);
        $this->assertDatabaseHas('menu_item_translations', [
                                'locale' => 'en',
                                'name' => 'Main menu',
                ]);
    }

    /** @test */
    /*public function it_displays_the_menu_items_index_page()
    {
        $this->authenticateAsAdmin();
        $this->get('menuItems')
            ->assertViewIs('laravel-quick-menus::menuItems.index')
            ->assertStatus(200);
    }*/

    /** @test */
    /*public function it_displays_the_menu_item_create_page()
    {
        $this->authenticateAsAdmin();
        $this->get('menuItems/create')
            ->assertViewIs('laravel-quick-menus::menuItems.create')
            ->assertStatus(200);
    }*/

    /** @test */
    /*public function it_stores_a_valid_menu_item()
    {
        $this->authenticateAsAdmin();

        $data = [
            'name' => 'test title',
            'slug' => 'test body',
        ];

        $response = $this
            ->followingRedirects()
            ->post('/menuItems', $data);

        $this->assertDatabaseHas('menu_item_translations', ['locale' => 'en']);
        $response->assertViewIs('laravel-quick-menus::menuItems.index');
    }*/

    /** @test */
    /*public function it_does_not_store_invalid_menu_item()
    {
        $this->authenticateAsAdmin();
        $response = $this->post('/menuItems', []);
        $response->assertSessionHasErrors();
        $this->assertNull(MenuItem::first());
    }*/

    /** @test */
    /*public function it_displays_the_menu_item_show_page()
    {
        $this->authenticate();

        $menuItem = factory(MenuItem::class)->create();
        $response = $this->get('/menuItems/'.$menuItem->id);
        $response->assertViewIs('laravel-quick-menus::menuItems.show')
                 ->assertStatus(200);
    }*/

    /** @test */
    /*public function it_displays_the_menu_item_edit_page()
    {
        $this->authenticateAsAdmin();

        $menuItem = factory(MenuItem::class)->create();
        $response = $this->get("/menuItems/{$menuItem->id}/edit");
        $response->assertViewIs('laravel-quick-menus::menuItems.edit')
                 ->assertStatus(200);
    }*/

    /** @test */
    /*public function it_updates_valid_menu_item()
    {
        $this->authenticateAsAdmin();
        $menuItem = factory(MenuItem::class)->create();

        $attributes = ([
            'name' => 'test name updated',
            'slug' => 'test slug updated',
          ]);

        $response = $this->followingRedirects()
                         ->put('/menuItems/'.$menuItem->id, $attributes);
        $response->assertViewIs('laravel-quick-menus::menuItems.index')
                 ->assertStatus(200);
    }*/

    /** @test */
    /*public function it_does_not_update_invalid_menu_item()
    {
        $this->authenticateAsAdmin();

        $menuItem = factory(MenuItem::class)->create();
        $response = $this->put('/menuItems/'.$menuItem->id, []);
        $response->assertSessionHasErrors();
    }*/

    /** @test */
    /*public function it_deletes_menu_items()
    {
        $this->authenticateAsAdmin();

        $menuItem = factory(MenuItem::class)->create();

        $response = $this->delete('/menuItems/'.$menuItem->id);
        $response->assertRedirect('/menuItems');
    }*/
}
