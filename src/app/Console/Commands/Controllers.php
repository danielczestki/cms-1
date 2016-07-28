<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class Controllers extends Commands
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:controllers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build controllers from installed YAML config files. <comment>(does not copy to the app/Cms directory)</comment>';
    
    /**
     * Path to the core folder
     * 
     * @var string
     */
    protected $corePath;
    
    /**
     * Path to the custom folder
     * 
     * @var string
     */
    protected $customPath;
    
    /**
     * Path to the core stub file
     * 
     * @var string
     */
    protected $stubCorePath;
    
    /**
     * Path to the custom stub file
     * 
     * @var string
     */
    protected $stubCustomPath;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->corePath = app_path("Cms/System/Http/Controllers");
        $this->customPath = app_path("Cms/Http/Controllers");
        $this->stubCorePath = realpath(__DIR__ . "/stubs/controller_core.stub");
        $this->stubCustomPath = realpath(__DIR__ . "/stubs/controller_custom.stub");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->getYamlFiles() as $filename) {
            $this->core($filename);
            $this->custom($filename);
        }
        $this->info("Controllers generated from YAML definitions successfully!");
    }
    
    /**
     * Build the core controller
     * 
     * @param  string $filename
     * @return void
     */
    private function core($filename)
    {
        $this->buildController("core", $filename, $this->stubCorePath, $this->corePath);
    }
    
    /**
     * Build the custom controller
     * 
     * @param  string $filename
     * @return void
     */
    private function custom($filename)
    {
        $this->buildController("custom", $filename, $this->stubCustomPath, $this->customPath);        
    }
    
    /**
     * Build the controller
     * 
     * @param  string $type
     * @param  string $filename
     * @param  string $stubpath
     * @param  string $savepath
     * @return void
     */
    private function buildController($type, $filename, $stubpath, $savepath)
    {
        $classname = $this->getControllerName($filename);
        $controllername = $classname . ".php";
        // if the controller is protected do not add/edit/overwrite/delete... don't touch it hear me!
        if ($type == "core" and in_array($controllername, $this->cms->getProtectedControllers(false))) return;
        // we are good to write
        $name = $this->getFileName($filename);
        $stub = file_get_contents($stubpath);
        $controller = str_ireplace(["{classname}", "{name}"], [$classname, $name], $stub);
        // save the file
        file_put_contents($savepath . "/" . $controllername, $controller);
    }
    
    /**
     * Return the class name
     * 
     * @param  string $filename
     * @return string
     */
    private function getControllerName($filename)
    {
        return $this->getFileName($filename) . "Controller";
    }
    
    
}
