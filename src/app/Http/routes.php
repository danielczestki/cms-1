<?php

use Symfony\Component\Finder\Finder;

Route::group(["prefix" => "admin", "middleware" => ["web"]], function () {

    // Core/uneditable routes (not copied over on publish)
    Route::group(["namespace" => "Thinmartian\Cms\App\Http\Controllers\Core"], function () {
        Route::get('login', 'Auth\AuthController@showLoginForm');
        Route::post('login', 'Auth\AuthController@login');
        Route::get('logout', 'Auth\AuthController@logout');
        // Password Reset Routes...
        Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
        Route::get('password/reset/{token}', 'Auth\PasswordController@showResetForm');
        Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm');
        Route::post('password/reset', 'Auth\PasswordController@reset');

        // api route
        Route::resource('api', 'ApiController', ["except" => "show"]);
    });

    // Custom/editable routes (copied over on publish)
    Route::group(["namespace" => "App\Cms\Http\Controllers", "middleware" => ["auth.cms", "web"]], function () {
        Route::group(["as" => "admin."], function () {
            // Build routes from the Yaml
            if (file_exists(app_path("Cms/Definitions/"))) {
                $finder = new Finder();
                foreach ($finder->in(app_path("Cms/Definitions/"))->name("*.yaml") as $file) {
                    $filename = CmsYaml::getFilename($file);
                    Route::group(["middleware" => ["permitted.cms:" . $filename, "web"]], function () use ($filename) {
                        Route::resource(strtolower($filename), $filename . "Controller", ["except" => "show", "parameters" => [strtolower($filename) => "id"]]);
                    });
                }


                // create the special media routes
                Route::get("media/type", ["as" => "admin.media.type", "uses" => "MediaController@type"]); // select the media type (image, document, video or embed)
                Route::get("media/focal/{cms_medium_id}", ["middleware" => ["cms.media.is:image", "web"], "as" => "admin.media.focal", "uses" => "MediaController@focal"]); // select the focal point for the image (image only)
                Route::post("media/focusing/{cms_medium_id}", ["middleware" => ["cms.media.is:image", "web"], "as" => "admin.media.focusing", "uses" => "MediaController@focusing"]); // set the focal point for the image (image only)
            }
        });

        // Predefined routes
        Route::get("/", ["uses" => function () {
            return view("cms::admin.dashboard.index");
        }])->name("cms-dashboard");
    });
});

// some api routes
Route::group(['prefix' => 'api', 'middleware' => 'isAllowedApi', 'namespace' => 'Thinmartian\Cms\App\Http\Controllers\Core'], function () {
    // loop through files
    if (file_exists(app_path('Cms/Definitions/'))) {
        $finder = new Finder();
        foreach ($finder->in(app_path('Cms/Definitions/'))->name('*.yaml') as $file) {
            $filename = CmsYaml::getFilename($file);
            $yaml = CmsYaml::parseYaml($file);

            if ($yaml && isset($yaml['meta']) && isset($yaml['meta']['api']) && $yaml['meta']['api']) {
                // get a single item
                $singular = str_singular($filename);
                // /api/users/{CmsUser}
                // /api/users/12
                // does not work unless we can type hint with a string :(
                //// Route::get(strtolower($filename) . '/{Cms' . ucwords($singular) . '}', function (Thinmartian\Cms\App\Models\Core\CmsAuthor $CmsAuthor) {
                ////    return $CmsAuthor;
                //// });

                // get a single ID
                // /api/users/{CmsUser}
                // /api/users/12
                Route::get(strtolower($filename) . '/{id}', [function ($id) use ($singular) {
                    return App::make('Thinmartian\Cms\App\Http\Controllers\Core\ApiController')->get($id, 'Thinmartian\Cms\App\Models\Core\Cms' . ucwords($singular));
                }]);

                // get all records
                // /api/users
                // /api/users?page=3&amount=5 (defaults to page 1, amount 10)
                Route::get(strtolower($filename), [function () use ($singular) {
                    return App::make('Thinmartian\Cms\App\Http\Controllers\Core\ApiController')->getAll('Thinmartian\Cms\App\Models\Core\Cms' . ucwords($singular));
                }]);
            }
        }
    }
});
