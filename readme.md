# Thin Martian CMS #

Thin Martian CMS platform built on the Laravel PHP Framework

----------

*IN DEVELOPMENT. BELOW IS A DUMP OF INFORMATION AND NOTES FOR NOW, WILL BE ORGANISED AT A LATER DATE*

----------

## Assets

To compile assets, use the usual `gulp` or `gulp watch` from within the `src` folder (e.g. `packages/thinmartiancms/cms/src`).

When a user uses the CMS all calls will be made to the laravel root `public/vendor/cms` folder, this would mean a `vendor:publish` artisan command would have to be run for every style change, this is far from ideal. So within your nginx conf, add the following to your `server` group

    location ~ ^/vendor/cms/(.*)$ {
        alias /var/www/thinmartian.com/cms/packages/thinmartiancms/cms/src/public/$1;
    }

Now all calls to `/css/vendor/cms/css/app.css` for example will be redirected to the `src/` compiled version. Of course, DO NOT add the above conf to the production site, the user must use `php artisan vendor:publish` for this.

**TODO: CHANGE THE `vendor:publish` MENTIONS ABOVE TO THE THINMARTIAN CMS ARTISAN COMMAND WHICH AT THE TIME OF WRITING THIS IS NOT YET KNOWN.**