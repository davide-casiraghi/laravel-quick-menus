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

$factory->define(DavideCasiraghi\LaravelQuickMenus\Models\MenuItemTranslation::class, function (Faker $faker) {
    $menu_item_name = $faker->name;
    $slug = Str::slug($menu_item_name, '-');

    return [
        'name' => $menu_item_name,
        'slug' => $slug,
        'locale' => 'en',
        'menu_item_id' => 1,
    ];
});
