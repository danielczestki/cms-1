# Thin Martian CMS #

Thin Martian CMS platform built on the Laravel PHP Framework

----------

*IN DEVELOPMENT. BELOW IS A DUMP OF INFORMATION AND NOTES FOR NOW, WILL BE ORGANISED AT A LATER DATE*

----------

## Setup and Installation

### System Requirements

- PHP 5.6+ (we use variadic functions)
- Laravel 5.2+
- Database (Any Laravel supported database)
- PHP GD library

### Install

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

## Configuration

Running `php artisan cms:build` will copy the Thin Martian CMS config file over to your app (`config/cms/cms.php`). Each value in the config is fully documented and no further instruction is required. But there are a few things to note.

### media config

Out the box, Thin Martian can upload media (images, documents etc) locally or in the cloud. This feature utilises  Laravel's [Filesystem / Cloud Storage](https://laravel.com/docs/filesystem) API. Therefore, if you wish to store files in cloud storage you will need to add a few dependencies to your app first as described in the Laravel documentation https://laravel.com/docs/5.2/filesystem#configuration.

Once all setup, you can change the `media_disk` config entry in `config/cms/cms.php` to match your `filesystem` entry that you wish to use as your default disk for media uploads.

Finally, ensure you set your `media_cloud_url` to the endpoint of your cloud storage, this endpoint will be used to server the media files back to the user.


## Resetting the CMS

If, for whatever reason you want to reset the entire CMS back to pre-install state you can do this by running:

    php artisan cms:destroy

**IMPORTANT**: This will delete **EVERYTHING** related to your Thin Martian CMS install. It will delete all generated content (models, controllers, migrations etc), drop all your generated database tables and delete all your custom `.yaml` definitions.


## YAML Definition API

*TODO, THIS IS A DUMPING GROUND RIGHT NOW AND IT FAR FROM COMPLETE*

### fields

Define the fields for the form.

	fields:
		fieldname:
			[options]

----------

The following options are global for all field types:

**type**   
`string` | *required*  
The type of field (all documented below).

**label**   
`string` | *required*  
The label for the field.

**value**   
`string` | *optional*  
Set a default value on the field

**placeholder**   
`string` | *optional*  
Set a placeholder on the field (if the field type accepts it)

**persist**   
`boolean` | *optional* | default: `false`   
Should this field be persisted to the database. If false, this field will not be in the migration and will not be stored (ideal for password_confirmation fields for example).

**validationOnCreate**   
`string` | *optional*   
Standard laravel piped validation rules. These rules will be applied on `create` only.

**validationOnUpdate**   
`string` | *optional*   
Standard laravel piped validation rules. These rules will be applied on `update` only.

**info**   
`string` | *optional*   
Display a small info tip next to the field.

**infoUpdate**   
`string` | *optional*   
Display a small info tip next to the field only on the `edit` screen.

**class**   
`string` | *optional*   
Add a custom css class(es) to the field.

**style**   
`string` | *optional*   
Add a custom css `style` attribute to the field.

**data-***   
`string` | *optional*   
Add a custom `data-*` attribute to the field.


----------


###`text`

*No special options.*

###`email`

*No special options.*

###`password`

*No special options.*

###`hidden`

*No special options.*

###`number`

**min**   
`integer` | *optional*  
The minimum number allowed.

###`textarea`

**rows**   
`integer` | *optional*  
Set the `rows` height of the `textarea`.

###`wysiwyg`

**rows**   
`integer` | *optional*  
Set the `rows` height of the `wysiwyg`.

###`select`

**options**   
`object` | *required*  
Key - value pair of the select options.

    category:
		type: "select"
    	label: "Select a category"
    	options: 
      		first: "First Category"
      		second: "Second Category"

###`checkbox`

*Same definition as `select`.*

###`radio`

*Same definition as `select`.*

###`boolean`

*No special options.*

###`date`

*No special options.*

###`datetime`

*No special options.*

###`media`
   
**limit**   
`integer` | *optional*  
Limit the amount of media items allowed. Remove for infinite.

**allowed**   
`array['image', 'video', 'document', 'embed']` | *optional*    
Restrict to certain media types. Remove for all types.

    media:
		type: "media"
		label: "Upload media"
		limit: 1
		allowed:
			- image
			- document

Example

    fields:
		firstname:
			type: "text"
			label: "Title"
			persist: true
			validationOnCreate: "required|max:20"
			validationOnUpdate: "required|max:20"
		usertype:
			type: "select"
			label: "User type"
			persist: true
			options:
				standard: "Standard user"
				admin: "Administrator"
		email:
			type: "email"
			label: "Email address"
			persist: true
			validationOnCreate: "required|email|max:255|unique:cms_tablename"
			validationOnUpdate: "required|email|max:255|unique:cms_tablename,email,{id}"
		password:
			type: "password"
			label: "Password"
			persist: true
			validationOnCreate: "required|min:6|confirmed"
			validationOnUpdate: "confirmed"
		password_confirmation:
			type: "password"
			label: "Confirm password"
			persist: false
			validationOnCreate: "required"
		photo:
			type: "media"
			label: "Select photo"
			allowed:
				- image
			limit: 1
			validationOnCreate: required
		media:
			type: media
			label: "Select media"
			


----------


## Contributing

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