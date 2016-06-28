# Thin Martian CMS #

Thin Martian CMS platform built on the Laravel PHP Framework

----------

*IN DEVELOPMENT. BELOW IS A DUMP OF INFORMATION AND NOTES FOR NOW, WILL BE ORGANISED AT A LATER DATE*

----------

## Install

> **TO DO: Package isn't currently on packagist, so use a `repositories: [{ type: 'vcs', url: 'path/to/git' }]` in your `composer.json` for now.**

Firstly, install the latest version of Laravel the usual way you do it, then:

Require the package from composer

    composer require thinmartian/cms

Update your Laravel `config/app.php` file by adding the below to the `providers` array:

    Thinmartian\Cms\CmsServiceProvider::class,

Now update your Laravel database config like normal by editing the `.env` in the root of your application. Ensure you have a successful connection to your database before proceeding.

Finally, open up a terminal and `cd` to the root of your Laravel install and run:

    php artisan cms:build

Allow the CMS to install and follow any prompts, then open up a browser, navigate to your Laravel app and go to `/admin` (e.g. `http://localhost/admin`) and you are set to go.

## Development

Follow these instructions to setup the Thin Martian CMS for development locally.

### Install Laravel

Go through the usual procedure to install Laravel, [follow the instructions](https://laravel.com/docs/5.2#installing-laravel) on the official Laravel website.

### Configure Laravel

Do your usual routine to get Laravel up and running (editing the config files etc). Ensure you setup a new database, and configure the `.env` file to utilise your new database. Then get Laravel booted in your browser.

### Create a folder for your development area

Create a `packages/thinmartian/cms` directory in the root of your newly installed Laravel project and git clone it with this repo.

You should end up with a structure like:

    packages/thinmartian/cms/
		src/
		tests/

### Install the node and composer dependencies

Easy, from the `packages/thinmartian/cms` directory run

    composer install

And 

	npm install

Also, we need to `npm install` from the root of your app, so `cd` to the root of your app and run `npm install` there also.

### Edit the root `composer.json`

Now you need to make a few changes to the root `composer.json` file. **NOT** the one you just cloned, the root of your Laravel project created by your Laravel install.

Make changes to the `autoload` section so it looks like this:

	...
	"psr-4": {
		"App\\": "app/",
		"Thinmartian\\Cms\\App\\": "packages/thinmartian/cms/src/app",
		"Thinmartian\\Cms\\": "packages/thinmartian/cms/src"
	},
    "files": [
    	"packages/thinmartian/cms/src/app/Support/helpers.php"
    ]
	...

We use two psr-4 loaders, as the CMS tries to conform to the exact same folder structure as Laravel, which means a lowercase `app/` directory. Not having the `Thinmartian\\Cms\\App\\` loader would cause errors on `composer update`'s as `php artisan optmize` would not be able to find a `/App` directory before a `composer dump`, and Laravel runs this command on it's own `post-update-cmd` before any `composer dump`'s.

Now look in the `packages/thinmartian/cms/composer.json` and copy across all the `require` packages that don't already exist in the root `composer.json` file, then:

    composer update

This will pull in the packages. As we haven't "installed" the CMS, composer knows nothing about our `packages/thinmartian/cms` vendor directory, so it won't load our packages from there, so in development we have to pull them in from the main Laravel app instead.

### Edit your Laravel config

Now we need to tell Laravel about our development version of Thin Martian CMS, open up your `config/app.php` in the root of your Laravel install and add the following to your `providers` array:

	Thinmartian\Cms\CmsServiceProvider::class,


### Dump the composer autoload classes

	composer dump -o
	php optimize

### Build the CMS

Simply run the below from the root of your Laravel project and follow the instructions:

	php artisan cms:build

### You're set

Navigate to `/admin` in your browser and you should be presented with a login screen, enter the admin details you just created and you are ready to code.

### Assets

To compile assets, use the usual `gulp` or `gulp watch` from within the `src` folder (e.g. `packages/thinmartian/cms/src`).

When a user uses the CMS all calls will be made to the laravel root `public/vendor/cms` folder, this would mean a `cms:build` artisan command would have to be run for every style change, this is far from ideal. So within your nginx conf, add something similar to the following to your `server` group

    location ~ ^/vendor/cms/(.*)$ {
        alias /path/to/thinmartiancms/packages/thinmartian/cms/src/public/$1;
    }

Now all calls to `/vendor/cms/css/app.css` for example will be redirected to the `src/` compiled version. Of course, DO NOT add the above conf to the production site, the user must use `php artisan cms:build` for this.

### Testing

The Thin Martian CMS by nature relies heavily... well completely on the Laravel framework and ecosystem and a fully installed and working copy of `laravel/laravel`, as all routes and classes are pulled from there. Therefore, a fully functioning CMS is required to run all tests.

The `phpunit.xml` in Thin Martian CMS repo you just installed uses the autoloader in the root of your laravel install `bootstrap/autoload.php`, this allows us to extend `Illuminate\Foundation\Testing\TestCase` and use most of Laravel's testing helpers.

Once you have installed your development environment as above, you should be able to simply run `phpunit` from your `packages/thinmartian/cms` directory.

If you get a phpunit error then:

1. Install phpunit globally as per the PHPUnit website instructions
2. Or, add `vendor/bin/phpunit` to your `$PATH`
3. Or, call `./vendor/bin/phpunit` instead of just `phpunit`

## Resetting the CMS

If, for whatever reason you want to reset the entire CMS back to pre-install state you can do this by running:

    php artisan cms:destroy

**IMPORTANT**: This will delete **EVERYTHING** related to your Thin Martian CMS install. It will delete all generated content (models, controllers, migrations etc), drop all your generated database tables and delete all your custom `.yaml` definitions.


## YAML Definition API

Coming soon!