<?php


Route::group(["prefix" => "admin", "namespace" => "Thinmartiancms\Cms\App\Http\Controllers", "middleware" => ["web"]], function () {
    
    Route::group(["middleware" => ["auth.cms"]], function() {
        Route::get("/", ["uses" => "DashboardController@index", "as" => "cms-dashboard"]);
    });
    
    Route::auth();
    
});