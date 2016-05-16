<?php

Route::get("/admin", function () {
    return view("cms::admin.dashboard.index");
});