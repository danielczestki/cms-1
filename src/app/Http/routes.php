<?php

use Symfony\Component\Finder\Finder;

Route::group(["prefix" => "admin", "middleware" => ["web"]], function () {
    
    // Core/uneditable routes (not copied over on publish)
    Route::group(["namespace" => "Thinmartian\Cms\App\Http\Controllers\Core"], function() {
        Route::auth();
    });
    
    // Custom/editable routes (copied over on publish)
    Route::group(["namespace" => "App\Cms\Http\Controllers", "middleware" => ["auth.cms"]], function() {
        
        Route::get("/", ["as" => "cms-dashboard", "uses" => function() {
            return view("cms::admin.dashboard.index");
        }]);
        
        // build routes from the Yaml
        $finder = new Finder();
        foreach ($finder->in(app_path("Cms/Definitions/"))->name("*.yaml") as $file) {
            $filename = $file->getBasename('.' . $file->getExtension());
            Route::resource(strtolower($filename), $filename . "Controller", ["except" => "show"]);
        }
        
    });
    
});