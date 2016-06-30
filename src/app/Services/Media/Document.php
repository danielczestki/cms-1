<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Services\Resource\ResourceInput;

class Document {
    
    use Media;
    
    /**
     * constructor
     */
    public function __construct() {}
    
    /**
     * Store the document
     * 
     * @param  ResourceInput $input
     * @return App\Cms\CmsMedium
     */
    public function store(ResourceInput $input)
    {
        dd("Storing document...");
    }
}