<?php namespace Thinmartiancms\Cms\App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thinmartiancms\Cms\App\Html\CmsFormBuilder
 */
class CmsFormFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return "cmsform"; }

}