<?php

namespace Thinmartian\Cms\App\Services\Definitions;

use Symfony\Component\Yaml\Parser;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Finder\Finder;

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
     * @var Symfony\Component\Finder\Finder
     */
    protected $finder;
    
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
        $this->finder = new Finder;
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
        if (! array_key_exists("listing", $this->yaml)) return null;
        // bind ID and dates to it, as they must always show
        $listing = array_merge([
            "id" => [
                "label" => "ID",
                "sortable" => true
            ]
        ], $this->yaml["listing"], [
            "created_at" => [
                "label" => "Date created",
                "sortable" => true
            ],
            "updated_at" => [
                "label" => "Date updated",
                "sortable" => true
            ]
        ]);
        return $listing;
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
     * Go through all the YAML config files and return the nav
     * based on what we have 
     * 
     * @return array
     */
    public function getNav()
    {
        $arr = [];
        $parser = new Parser();
        $files = $this->finder->files()->in($this->path)->name("*.yaml");;
        foreach($files as $file) {
            $yaml = $parser->parse(file_get_contents($file->getRealpath()));
            // do loads of validation to be safe
            if (! is_array($yaml)) continue;
            if (! array_key_exists("meta", $yaml)) continue;
            $meta = $yaml["meta"];
            if (! array_key_exists("show_in_nav", $meta)) continue;
            if (! $meta["show_in_nav"]) continue;
            if (! array_key_exists("title", $meta)) continue;
            $filename = $file->getBasename('.' . $file->getExtension());
            $arr[$filename] = [
                "title" => $meta["title"],
                "icon" => array_get($meta, "icon") ?: "folder",
                "url" => @route("admin.". strtolower($filename) .".index"),
                "controller" => $filename . "Controller"
            ];
        }
        return $arr;
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