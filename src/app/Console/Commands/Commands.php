<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class Commands extends Command
{
    
    /**
     * Simple flag to know if a model was generated for the console response
     * 
     * @var boolean
     */
    protected $created = false;
    
    /**
     * @var Thinmartian\Cms\App\Services\Cms
     */
    protected $cms;
    
    /**
     * @var Symfony\Component\Yaml\Parser
     */
    protected $yaml;
        
    /**
     * Path to the YAML config files
     * 
     * @var string
     */
    protected $yamlPath;
    
    /**
     * Prefix for the cms tables
     */
    const TABLEPREFIX = "cms";
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->yaml = new Parser;
        $this->cms = app()->make("Thinmartian\Cms\App\Services\Cms");
        $this->yamlPath = app_path("Cms/Definitions/");
    }
    
    /**
     * Get the filename from the yaml config to be used for the table
     * 
     * @param  string $filename
     * @return string
     */
    protected function getFilename($filename)
    {
        return pathinfo($filename, PATHINFO_FILENAME);
    }
    
    /**
     * Get the db table name 
     * 
     * @param  string $name The yaml config filename
     * @return string
     */
    protected function getTablename($name)
    {
        return self::TABLEPREFIX . "_" . trim(strtolower(str_plural($name)));
    }
    
    /**
     * Get the full path to the yaml file
     * 
     * @param  string $filename
     * @return string
     */
    protected function getFullYamlPath($filename)
    {
        return $this->yamlPath . $filename;
    }
    
    /**
     * Return all the yaml config files in the definitions directory
     * 
     * @return array
     */
    protected function getYamlFiles()
    {
        $arr = [];
        foreach (scandir($this->yamlPath) as $filename) {
            if (ends_with($filename, ".yaml")) $arr[] = $filename;
        }
        return $arr;
    }
    
}
