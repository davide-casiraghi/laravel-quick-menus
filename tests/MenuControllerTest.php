<?php

namespace DavideCasiraghi\LaravelQuickMenus\Tests;

use Illuminate\Foundation\Testing\WithFaker;
use DavideCasiraghi\LaravelQuickMenus\Models\Menu;

class MenuControllerTest extends TestCase
{
    use WithFaker;

    /***************************************************************/

    /** @test */
    public function it_runs_the_menu_test_factory()
    {
        $menu = factory(Menu::class)->create();
        $this->assertDatabaseHas('menus', [
                                'id' => '1',
                                ]);
    }

    /** @test */
    public function it_displays_the_menus_index_page()
    {
        $this->authenticateAsAdmin();
        $this->get('menus')
                ->assertViewIs('laravel-quick-menus::menus.index')
                ->assertStatus(200);
    }

    /** @test */
    public function it_displays_the_menu_create_page()
    {
        $this->authenticateAsAdmin();
        $this->get('menus/create')
            ->assertViewIs('laravel-quick-menus::menus.create')
            ->assertStatus(200);
    }

    /** @test */
    public function it_stores_a_valid_menu()
    {
        $this->authenticateAsAdmin();

        $attributes = factory(Menu::class)->raw();
        $response = $this->post('/menus', $attributes);
        $menu = Menu::first();

        //$this->assertDatabaseHas('menus', $attributes);
        $response->assertRedirect('/menus/');
    }

    /** @test */
    public function it_does_not_store_invalid_menu()
    {
        $this->authenticateAsAdmin();

        $response = $this->post('/menus', []);
        $response->assertSessionHasErrors();
        $this->assertNull(Menu::first());
    }

    /** @test */
    /*public function it_displays_the_menu_show_page()
    {
        $this->authenticateAsAdmin();

        $menu = factory(Menu::class)->create();

        $response = $this->get('/menus/'.$menu->id)->dump();
        //$response->assertViewIs('laravel-quick-menus::menus.show')
        //         ->assertStatus(200);
    }*/

    /** @test */
    public function it_displays_the_menu_edit_page()
    {
        $this->authenticateAsAdmin();

        $menu = factory(Menu::class)->create();
        $response = $this->get("/menus/{$menu->id}/edit");
        $response->assertViewIs('laravel-quick-menus::menus.edit')
                 ->assertStatus(200);
    }

    /** @test */
    public function it_updates_valid_menu()
    {
        // https://www.neontsunami.com/posts/scaffolding-laravel-tests
        $this->authenticateAsAdmin();

        $menu = factory(Menu::class)->create();
        $attributes = factory(Menu::class)->raw(['name' => 'Updated']);

        $response = $this->put("/menus/{$menu->id}", $attributes);
        $response->assertRedirect('/menus/');
        $this->assertEquals('Updated', $menu->fresh()->name);
    }

    /** @test */
    public function it_does_not_update_invalid_menu()
    {
        $this->authenticateAsAdmin();

        $menu = factory(Menu::class)->create(['name' => 'Example']);
        $response = $this->put("/menus/{$menu->id}", []);
        $response->assertSessionHasErrors();
        $this->assertEquals('Example', $menu->fresh()->name);
    }

    /** @test */
    public function it_deletes_menus()
    {
        $this->authenticateAsAdmin();

        $menu = factory(Menu::class)->create();
        $response = $this->delete("/menus/{$menu->id}");
        $response->assertRedirect('/menus');
        $this->assertNull($menu->fresh());
    }
}
