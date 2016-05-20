<?php


Route::group(["prefix" => "admin", "middleware" => ["web"]], function () {
    
    // Core/uneditable routes (not copied over on publish)
    Route::group(["namespace" => "Thinmartian\Cms\App\Http\Controllers\Core"], function() {
        Route::auth();
    });
    
    // Custom/editable routes (copied over on publish)
    // TODO: Put namespace back to App\Cms\Http\Controllers as that is what we look at after we copy controllers over
    Route::group(["namespace" => "App\Cms\Http\Controllers", "middleware" => ["auth.cms"]], function() {
        
        Route::get("/", ["as" => "cms-dashboard", "uses" => function() {
            return view("cms::admin.dashboard.index");
        }]);
        
        /* START: routes to be generated by YAML */
        Route::resource("users", "UsersController", ["except" => "show"]);
        /* END: routes to be generated by YAML */
        
    });
    
});