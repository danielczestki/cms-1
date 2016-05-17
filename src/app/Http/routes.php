<?php


Route::group(["prefix" => "admin", "namespace" => "Thinmartiancms\Cms\App\Http\Controllers", "middleware" => ["web"]], function () {
    
    // Auth routes (they have their middleware declared in the controller)
    Route::auth();
    
    // Guest routes
    Route::group(["middleware" => ["auth.cms"]], function() {
        //
    });
    
    // Member only routes
    Route::group(["middleware" => ["auth.cms"]], function() {
        Route::get("/", ["uses" => "DashboardController@index", "as" => "cms-dashboard"]);
    });
    
});