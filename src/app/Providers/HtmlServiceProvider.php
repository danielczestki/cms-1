<?php

namespace Thinmartiancms\Cms\App\Providers;

use Illuminate\Support\ServiceProvider;
use Thinmartiancms\Cms\App\Html\CmsFormBuilder;

class HtmlServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerFormBuilder();

        $this->app->alias("cmsform", "Thinmartiancms\Cms\App\Html\CmsFormBuilder");
    }

    /**
     * Register the CMS Form builder instance.
     *
     * @return void
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('cmsform', function ($app) {
            return new CmsFormBuilder();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array("cmsform", "Thinmartiancms\Cms\App\Html\CmsFormBuilder");
    }

}
