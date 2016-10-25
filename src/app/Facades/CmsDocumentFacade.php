<?php

namespace Thinmartian\Cms\App\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Thinmartian\Cms\App\Services\Media\Document
 */
class CmsDocumentFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return "cmsdocument";
    }
}
