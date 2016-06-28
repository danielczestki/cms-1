<?php

namespace Thinmartian\Cms\App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thinmartian\Cms\App\Services\Media\Video
 */
class CmsVideoFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return "cmsvideo"; }

}