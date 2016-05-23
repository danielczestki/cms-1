<?php

define("CMSNAMESPACE", "\Thinmartian\Cms\App\Http\Controllers\\");
define("APPNAMESPACE", "\App\Cms\Http\Controllers\\");

if (! function_exists("cmsaction")) {
    /**
     * Return a path based on the CMS action
     *
     * @param  string  $action  The action within the CMS namespace
     * @param  boolean $app     App namespace?
     * @return string
     */
    function cmsaction($action, $app = false)
    {
        return action(($app ? APPNAMESPACE : CMSNAMESPACE) . $action);
    }
}

if (! function_exists("de")) {
    /**
     * Like dd() but will utilist xdebug (if you have it)
     * 
     * @param  mixed
     * @return void
     */
    function de()
    {
        array_map(function ($x) {
            var_dump($x);
        }, func_get_args());

        die(1);
    }
}