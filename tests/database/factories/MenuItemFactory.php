<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(DavideCasiraghi\LaravelQuickMenus\Models\MenuItem::class, function (Faker $faker) {
    $menu_item_name = $faker->name;
    $slug = Str::slug($menu_item_name, '-');

    return [
        'name:en' => $menu_item_name,
        'slug:en' => $slug,
        'parent_item_id' => null,
        'url' => null,
        'font_awesome_class' => null,
        'route' => null,
        'type' => null,
        'menu_id' => 1,
        'order' => 1,
    ];
});
