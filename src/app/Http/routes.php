<?php

use Symfony\Component\Finder\Finder;

Route::group(["prefix" => "admin", "middleware" => ["web"]], function () {
    
    // Core/uneditable routes (not copied over on publish)
    Route::group(["namespace" => "Thinmartian\Cms\App\Http\Controllers\Core"], function() {
        Route::auth();
    });
    
    // Custom/editable routes (copied over on publish)
    Route::group(["namespace" => "App\Cms\Http\Controllers", "middleware" => ["auth.cms"]], function() {
                
        // Build routes from the Yaml
        if (file_exists(app_path("Cms/Definitions/"))) {
            $finder = new Finder();
            foreach ($finder->in(app_path("Cms/Definitions/"))->name("*.yaml") as $file) {
                $filename = $file->getBasename('.' . $file->getExtension());
                Route::resource(strtolower($filename), $filename . "Controller", ["except" => "show", "parameters" => [strtolower($filename) => "id"]]);
            }
        }
        
        // Predefined routes
        Route::get("/", ["as" => "cms-dashboard", "uses" => function() {
            return view("cms::admin.dashboard.index");
        }]);
        
    });
    
});