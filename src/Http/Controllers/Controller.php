<?php

namespace DavideCasiraghi\LaravelQuickMenus\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // **********************************************************************

    /**
     * Get the current logged user ID.
     * If user is admin or super admin return 0.
     *
     * @return int $ret
     */
    public function getLoggedUser()
    {
        $ret = Auth::user();

        return $ret;
    }

    // **********************************************************************

    /**
     * Get the current logged user id.
     *
     * @return bool $ret - the current logged user id, if admin($user->group == 2) or super admin()$user->group == 1) is stet to 0
     */
    public function getLoggedAuthorId()
    {
        $user = Auth::user();
        $ret = null;
        if ($user) {
            //$ret = (! $user->isSuperAdmin() && ! $user->isAdmin()) ? $user->id : 0;
            $ret = (! $user->group == 1 && ! $user->group == 2) ? $user->id : 0;
        }

        return $ret;
    }

    // **********************************************************************

    /**
     * Get the language name from language code.
     *
     * @param  string $languageCode
     * @return string
     */
    public function getSelectedLocaleName($languageCode)
    {
        $countriesAvailableForTranslations = LaravelLocalization::getSupportedLocales();
        $ret = $countriesAvailableForTranslations[$languageCode]['name'];

        return $ret;
    }
}
