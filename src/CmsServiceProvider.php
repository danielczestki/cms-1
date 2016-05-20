<?php

namespace Thinmartian\Cms;

use Auth, Request;
use Thinmartian\Cms\App\Models\Core\CmsUser;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Collective\Html\HtmlServiceProvider;
use Thinmartian\Cms\App\Http\Middleware\Authenticate;
use Thinmartian\Cms\App\Http\Middleware\RedirectIfAuthenticated;
use Thinmartian\Cms\App\Html\CmsFormBuilder;
use Thinmartian\Cms\App\Services\Definitions\Yaml as CmsYaml;


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
    public function boot(Router $router)
    {
        $this->bootRoutes();
        $this->bootViews();
        $this->registerMiddleware($router);
        //$this->publishViews();
        $this->publishDefinitions();
        $this->publishConfig();
        $this->publishAssets();
        $this->publishMigrations();
        $this->publishModels();
        $this->publishControllers();
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
        $this->updateConfig();
        $this->registerFormBuilder();
        $this->registerYaml();
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
     * Register middleware with the Kernal
     * 
     * @return void
     */
    private function registerMiddleware(Router $router)
    {
        $router->middleware("auth.cms", Authenticate::class);
        $router->middleware("guest.cms", RedirectIfAuthenticated::class);
    }
    
    /**
     * Publish the stock definitions
     * 
     * @return void
     */
    private function publishDefinitions()
    {
        $this->publishes([
            __DIR__."/app/Definitions" => app_path("Cms/Definitions")
        ], "definitions");
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
     * Publish the models
     * 
     * @return void
     */
    private function publishModels()
    {
        $this->publishes([
            __DIR__."/app/Models/Custom" => app_path("Cms"),
        ], "models");
    }
    
    /**
     * Publish the controllers
     * 
     * @return void
     */
    private function publishControllers()
    {
        $this->publishes([
            __DIR__."/app/Http/Controllers/Custom" => app_path("Cms/Http/Controllers"),
        ], "controllers");
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
        $this->loader->alias("Form", "Collective\Html\FormFacade");
        $this->loader->alias("Html", "Collective\Html\HtmlFacade");
        $this->loader->alias("CmsForm", "Thinmartian\Cms\App\Facades\CmsFormFacade");
        $this->loader->alias("CmsYaml", "Thinmartian\Cms\App\Facades\CmsYamlFacade");
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
        // if within /admin, force the default guard and passwords to cms
        if (Request::is("admin*")) {
            config(["auth.defaults.guard" => self::NAME]);
            config(["auth.defaults.passwords" => self::NAME]);
        }
    }    
    
    /**
     * Register the CMS form builder
     * 
     * @return void
     */
    private function registerFormBuilder()
    {
        $this->app->singleton('cmsform', function ($app) {
            return new CmsFormBuilder();
        });
        $this->app->alias("cmsform", CmsFormBuilder::class);
    }
    
    /**
     * Register the CMS Yaml
     * 
     * @return void
     */
    private function registerYaml()
    {
        $this->app->singleton('cmsyaml', function ($app) {
            return new CmsYaml();
        });
        $this->app->alias("cmsyaml", CmsYaml::class);
    }
    
    /**
     * Get the services provided by the provider for the form builder.
     *
     * @return array
     */
    public function provides()
    {
        return ["cmsform", "cmsyaml", CmsFormBuilder::class, CmsYaml::class];
    }
    
}