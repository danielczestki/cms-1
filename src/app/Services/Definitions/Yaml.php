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
        return array_key_exists("meta", $this->yaml) ? $this->yaml["meta"] : null;
    }
    
    /**
     * Get the listing for the index grid listing page for a resource
     * 
     * @return array
     */
    public function getListing()
    {
        return array_key_exists("listing", $this->yaml) ? $this->yaml["listing"] : null;
    }
    
    /**
     * Get the form fields for the resource
     * 
     * @return array
     */
    public function getFields()
    {
        return array_key_exists("fields", $this->yaml) ? $this->yaml["fields"] : null;
    }
    
    /**
     * Get the searchable fields for the resource
     * 
     * @return array
     */
    public function getSearchable()
    {
        return array_key_exists("searchable", $this->yaml) ? $this->yaml["searchable"] : [];;
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
            $this->yaml = $yaml->parse(file_get_contents($this->file));
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