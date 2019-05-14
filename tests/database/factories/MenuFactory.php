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

$factory->define(DavideCasiraghi\LaravelQuickMenus\Models\Menu::class, function (Faker $faker) {
    
    return [
        'name' => $faker->word." menu",
        'position' => 1,
        'order' => 1,
    ];
});
