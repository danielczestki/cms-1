<?php


Route::group(["prefix" => "admin"], function () {
    Route::get("/", function () {
        return view("cms::admin.dashboard.index");
    });
    
    //Route::auth();
    
});