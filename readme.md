# Thin Martian CMS #

Thin Martian CMS platform built on the Laravel PHP Framework

----------

*IN DEVELOPMENT. BELOW IS A DUMP OF INFORMATION AND NOTES FOR NOW, WILL BE ORGANISED AT A LATER DATE*

----------

## Install

Firstly, install the latest version of Laravel the usual way you do it, then:

Require the package from composer

    composer require thinmartian/cms

Update your Laravel `config/app.php` file by adding the below to the `providers` array:

    Thinmartian\Cms\CmsServiceProvider::class,

Finally, open up a terminal and `cd` to the root of your Laravel install and run:

    php artisan cms:build

Allow the CMS to install and follow any prompts, then open up a browser, navigate to your Laravel app and go to `/admin` (e.g. `http://localhost/admin`) and you are set to go.

## Assets

To compile assets, use the usual `gulp` or `gulp watch` from within the `src` folder (e.g. `packages/thinmartiancms/cms/src`).

When a user uses the CMS all calls will be made to the laravel root `public/vendor/cms` folder, this would mean a `cms:build` artisan command would have to be run for every style change, this is far from ideal. So within your nginx conf, add something similar to the following to your `server` group

    location ~ ^/vendor/cms/(.*)$ {
        alias /var/www/thinmartiancms/packages/thinmartian/cms/src/public/$1;
    }

Now all calls to `/vendor/cms/css/app.css` for example will be redirected to the `src/` compiled version. Of course, DO NOT add the above conf to the production site, the user must use `php artisan cms:build` for this.
