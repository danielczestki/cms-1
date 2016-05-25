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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->yaml = new Parser;
        $this->yamlPath = app_path("Cms/Definitions/");
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
