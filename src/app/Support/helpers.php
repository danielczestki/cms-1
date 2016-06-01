<?php

define("CMSNAMESPACE", "\Thinmartian\Cms\App\Http\Controllers\\");
define("APPNAMESPACE", "\App\Cms\Http\Controllers\\");



if (! function_exists("in_nav")) {
    
    function in_nav($controller = null, $action = null)
    {
        $route = request()->route()->getAction();
        if (! array_key_exists("controller", $route) and ! $controller) return true; // no controller and empty controller, so prob homepage, so true
        if (! array_key_exists("controller", $route)) return false;
        $_controller = class_basename($route["controller"]);
        list($_controller, $_action) = explode("@", $_controller);
        if ($action) {
            // controller and action
            return ($_controller == $controller and $_action == $action);
        } else {
            // just controller
            return $_controller == $controller;
        }
    }
}




if (! function_exists("gravatar")) {
    /**
     * Return a users gravatar
     *
     * @param  string  $email
     * @param  integer $size    Defaults to 120
     * @return string
     */
    function gravatar($email, $size = 120)
    {
        return "https://s.gravatar.com/avatar/". md5($email) ."?s={$size}";
    }
}

if (! function_exists("cmsaction")) {
    /**
     * Return a path based on the CMS action
     *
     * @param  string  $action  The action within the CMS namespace
     * @param  boolean $app     App namespace?
     * @return string
     */
    function cmsaction($action, $app = false, $params = [])
    {
        return action(($app ? APPNAMESPACE : CMSNAMESPACE) . $action, $params);
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

if (! function_exists("cmsfillable")) {
    /**
     * Builds the fillable array for models, here so it can be shared
     * with the special/stock CmsUser model which doesn't extend the
     * core Model like all others
     * 
     * @param string $yaml
     * @return array
     */
    function cmsfillable($yaml)
    {
        $arr = [];
        $parser = new \Symfony\Component\Yaml\Parser;
        $yaml = $parser->parse(file_get_contents(app_path("Cms/Definitions/{$yaml}.yaml")));
        $fields = $yaml["fields"];
        foreach ($fields as $idx => $data) {
            if (array_key_exists("persist", $data) and ! $data["persist"]) {} else {
                $arr[] = $idx;
            }
        }
        return $arr;
    }
}