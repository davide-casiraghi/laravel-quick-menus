<?php

namespace DavideCasiraghi\LaravelQuickMenus;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

class LaravelQuickMenusServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-quick-menus');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-quick-menus');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->app['router']->aliasMiddleware('admin', \DavideCasiraghi\LaravelQuickMenus\Http\Middleware\Admin::class);

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('laravel-quick-menus.php'),
            ], 'config');

            // Publishing the views.
            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-quick-menus'),
            ], 'views');

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-quick-menus'),
            ], 'assets');*/

            // Publishing the translation files.
            $this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-quick-menus'),
            ], 'lang');

            // Registering package commands.
            // $this->commands([]);

            /* - Migrations -
               create a migration instance for each .php.stub file eg.
               create_continents_table.php.stub --->  2019_04_28_190434761474_create_continents_table.php
            */
            $migrations = [
                     'CreateMenusTable' => 'create_menus_table',
                     'CreateMenuItemsTable' => 'create_menu_items_table',
                     'CreateMenuItemTranslationsTable' => 'create_menu_item_translations_table',
                 ];

            foreach ($migrations as $migrationFunctionName => $migrationFileName) {
                if (! class_exists($migrationFunctionName)) {
                    $this->publishes([
                             __DIR__.'/../database/migrations/'.$migrationFileName.'.php.stub' => database_path('migrations/'.Carbon::now()->format('Y_m_d_Hmsu').'_'.$migrationFileName.'.php'),
                         ], 'migrations');
                }
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-quick-menus');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-quick-menus', function () {
            return new LaravelQuickMenus;
        });
    }
}
