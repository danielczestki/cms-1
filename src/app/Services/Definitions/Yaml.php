<?php

namespace Thinmartian\Cms\App\Services\Definitions;

class Yaml {
    
    /**
     * @var string
     */
    protected $definitionsPath;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $this->path = app_path("Cms/Definitions");
    }
    
    public function testme()
    {
        return "yaml working";
    }
    
}