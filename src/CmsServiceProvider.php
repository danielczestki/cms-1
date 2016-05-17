<?php

namespace Thinmartiancms\Cms;

use Auth;
use Thinmartiancms\Cms\App\CmsUser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Collective\Html\HtmlServiceProvider;
use Thinmartiancms\Cms\App\Providers\HtmlServiceProvider as CmsHtmlServiceProvider;

class CmsServiceProvider extends ServiceProvider
{   
    
    /**
     * Name of the package
     */
    const NAME = "cms";
    
    /**
     * @var Illuminate\Foundation\AliasLoader
     */
    protected $loader;
    
    /**
     * Create the Thin Martian CMS service provider instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        parent::__construct($app);
        $this->loader = AliasLoader::getInstance();
    }
    
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->bootRoutes();
        $this->bootViews();
        //$this->publishViews();
        $this->publishConfig();
        $this->publishAssets();
        $this->publishMigrations();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->bindHtml();
        $this->updateConfig(); // can go in boot().. try here first!
    }
    
    /**
     * Boot the routes
     * 
     * @return void
     */
    private function bootRoutes()
    {
        if (! $this->app->routesAreCached()) {
            require __DIR__."/app/Http/routes.php";
        }
    }
    
    /**
     * Boot the views
     * 
     * @return void
     */
    private function bootViews()
    {
        $this->loadViewsFrom(__DIR__."/resources/views", self::NAME);
    }
    
    /**
     * Publish the views
     * 
     * @return void
     */
    /*private function publishViews()
    {
        $this->publishes([
            __DIR__."/resources/views" => base_path("resources/views/vendor/".self::NAME),
        ], "views");
    }*/
    
    /**
     * Publish the configs
     * 
     * @return void
     */
    private function publishConfig()
    {
        $this->publishes([
            __DIR__."/config/".self::NAME."/cms.php" => config_path(self::NAME."/cms.php")
        ], "config");
    }
    
    /**
     * Publish the public assets
     * 
     * @return void
     */
    private function publishAssets()
    {
        $this->publishes([
            __DIR__."/public" => public_path("vendor/".self::NAME),
        ], "assets");
    }
    
    /**
     * Publish the database migrations
     * 
     * @return void
     */
    private function publishMigrations()
    {
        $this->publishes([
            __DIR__."/database/migrations" => database_path("migrations"),
        ], "migrations");
    }
    
    /**
     * Merge the config files in vendor with the ones published
     * 
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__."/config/".self::NAME."/cms.php", self::NAME.".cms"
        );
    }
    
    /**
     * Bind the Laravel Collective HTML package (Collective\Html\HtmlServiceProvider) to the IoC
     * 
     * @return void
     */
    private function bindHtml()
    {
        $this->app->register(HtmlServiceProvider::class);
        $this->app->register(CmsHtmlServiceProvider::class);
        $this->loader->alias("Form", "Collective\Html\FormFacade");
        $this->loader->alias("Html", "Collective\Html\HtmlFacade");
        $this->loader->alias("CmsForm", "Thinmartiancms\Cms\App\Facades\CmsFormFacade");
    }
    
    /**
     * Update config values (including default laravel config)
     * 
     * @return void
     */
    private function updateConfig()
    {
        $this->updateConfigAuth();
    }
    
    /**
     * Update the default auth config to add the Thin Martian CMS guard
     * 
     * @return void
     */
    private function updateConfigAuth()
    {
        // guard
        config(["auth.guards.".self::NAME => [
            "driver" => "session",
            "provider" => "cms",
        ]]);
        // provider
        config(["auth.providers.".self::NAME => [
            "driver" => "eloquent",
            "model" => CmsUser::class
        ]]);
        // password (reset)
        config(["auth.passwords.".self::NAME => [
            "provider" => self::NAME,
            "email" => self::NAME."::admin.auth.emails.password",
            "table" => "cms_password_resets",
            "expire" => 60,
        ]]);
        // now change the default, we don't use anything but cms guard here
        config(["auth.defaults.guard" => self::NAME]);
    }    
    
}