<?php

define("CMSNAMESPACE", "\Thinmartian\Cms\App\Http\Controllers\\");

if (! function_exists("cmsaction")) {
    /**
     * Return a path based on the CMS action
     *
     * @param  string  $action  The action within the CMS namespace
     * @return string
     */
    function cmsaction($action)
    {
        return action(CMSNAMESPACE . $action);
    }
}