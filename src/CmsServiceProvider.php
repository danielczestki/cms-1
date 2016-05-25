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
use Thinmartian\Cms\App\Services\Definitions\Yaml as CmsYamlService;


class CmsServiceProvider extends ServiceProvider
{   
    
    /**
     * Name of the package
     */
    const NAME = "cms";
    
    /**
     * @var array
     */
    protected $commands = [
        \Thinmartian\Cms\App\Console\Commands\Build::class,
        \Thinmartian\Cms\App\Console\Commands\Migrations::class,
        \Thinmartian\Cms\App\Console\Commands\Models::class
    ];
    
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
        $this->bootMiddleware($router);
        $this->publishDefinitions();
        $this->publishConfig();
        $this->publishAssets();
        $this->publishMigrations();
        $this->publishModels();
        $this->publishControllers();
        $this->browserActions();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfig();
        $this->updateConfig();
        $this->registerYaml();
        $this->registerFormBuilder();
        $this->registerCommands();
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
    private function bootMiddleware(Router $router)
    {
        $router->middleware("auth.cms", Authenticate::class);
        $router->middleware("guest.cms", RedirectIfAuthenticated::class);
    }
    
    /**
     * Run stuff when in a browser only, and NOT in console/artisan
     * 
     * @return void
     */
    private function browserActions()
    {if (! app()->runningInConsole()) {
        if (! file_exists(app_path("Cms"))) {
            echo "Please run php artisan cms:build";
            exit;
        }
    }}
    
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
     * Register artisan commands
     * 
     * @return void
     */
    private function registerCommands()
    {        
        $this->commands($this->commands);
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
        $this->app->register(HtmlServiceProvider::class);
        $this->app->singleton('cmsform', function ($app) {
            return new CmsFormBuilder;
        });
        $this->app->alias("cmsform", CmsFormBuilder::class);
        $this->loader->alias("Form", "Collective\Html\FormFacade");
        $this->loader->alias("CmsForm", "Thinmartian\Cms\App\Facades\CmsFormFacade");
    }
    
    /**
     * Register the CMS Yaml
     * 
     * @return void
     */
    private function registerYaml()
    {
        $this->app->singleton('cmsyaml', function ($app) {
            return new CmsYamlService;
        });
        $this->app->alias("cmsyaml", CmsYamlService::class);
        $this->loader->alias("CmsYaml", "Thinmartian\Cms\App\Facades\CmsYamlFacade");
    }
    
}