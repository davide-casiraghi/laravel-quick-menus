<?php

namespace DavideCasiraghi\LaravelQuickMenus\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DavideCasiraghi\LaravelQuickMenus\Skeleton\SkeletonClass
 */
class LaravelQuickMenus extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-quick-menus';
    }
}
