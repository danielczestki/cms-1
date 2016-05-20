<?php

namespace Thinmartian\Cms\App\Services\Definitions;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;

class Yaml {
    
    /**
     * @var string
     */
    protected $prefix = "Cms";
    
    /**
     * @var string
     */
    protected $path;
    
    /**
     * @var string
     */
    protected $file;
    
    /**
     * @var object
     */
    protected $yaml;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $this->path = app_path("Cms/Definitions/");
    }
    
    
    /**
     * Getters
     */
    
    /**
     * Get the meta from the Yaml
     * 
     * @return array
     */
    public function getMeta()
    {
        return property_exists($this->yaml, "meta") ? $this->yaml->meta : null;
    }
    
    public function getListing()
    {
        return property_exists($this->yaml, "listing") ? $this->yaml->listing : [];
    }
    
    /**
     * Setters
     */
    
    /**
     * Set the name and filename we are currently using to the property
     * 
     * @param string $name The $modelName from the controller, will match the .yaml filename
     */
    public function setFile($name)
    {
        $this->file = $this->path . $name . ".yaml";
        if (file_exists($this->file)) {
            $yaml = new Parser();
            $this->yaml = $yaml->parse(file_get_contents($this->file), false, false, true);
            
            //de($this->yaml);
            
        } else {
            throw new ParseException("{$this->file} doesn't exist");
        }
    }
    
    /**
     * File utils
     */
    
    /**
     * Fetch the currently set file path
     * 
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * Fetch the currently set yaml object
     * 
     * @return string
     */
    public function getYaml()
    {
        return $this->yaml;
    }
    
}