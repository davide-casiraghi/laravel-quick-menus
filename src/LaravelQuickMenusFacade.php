<?php

namespace DavideCasiraghi\LaravelQuickMenus;

use Illuminate\Support\Facades\Facade;

/**
 * @see \DavideCasiraghi\LaravelQuickMenus\Skeleton\SkeletonClass
 */
class LaravelQuickMenusFacade extends Facade
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
