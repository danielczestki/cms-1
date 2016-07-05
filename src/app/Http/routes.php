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
            // create the special media routes
            Route::get("media/type", ["as" => "admin.media.type", "uses" => "MediaController@type"]); // select the media type (image, document, video or embed)
            Route::get("media/focal/{cms_medium_id}", ["middleware" => "cms.media.is:image", "as" => "admin.media.focal", "uses" => "MediaController@focal"]); // select the focal point for the image (image only)
            Route::post("media/focusing/{cms_medium_id}", ["middleware" => "cms.media.is:image", "as" => "admin.media.focusing", "uses" => "MediaController@focusing"]); // set the focal point for the image (image only)
        }
        
        // Predefined routes
        Route::get("/", ["as" => "cms-dashboard", "uses" => function() {
            return view("cms::admin.dashboard.index");
        }]);
        
    });
    
});

// Media urls
// cms/media/1/image/Rc9HIXycL8xtwLL-300x300.jpeg 
Route::group(["prefix" => "cms/media", "namespace" => "Thinmartian\Cms\App\Http\Controllers"], function () {
    Route::get("{cms_medium_id}/{type}/{filename}-{width}x{height}.{extension}", ["as" => "cms.media", "uses" => "CmsController@media"]);
});