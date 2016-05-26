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
        $this->corePath = realpath(__DIR__ . "/../../Http/Controllers/Core/");
        $this->customPath = realpath(__DIR__ . "/../../Http/Controllers/Custom/");
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
        $this->buildController($filename, $this->stubCorePath, $this->corePath);
    }
    
    /**
     * Build the custom controller
     * 
     * @param  string $filename
     * @return void
     */
    private function custom($filename)
    {
        $this->buildController($filename, $this->stubCustomPath, $this->customPath);        
    }
    
    /**
     * Build the controller
     * 
     * @param  string $filename
     * @param  string $stubpath
     * @param  string $savepath
     * @return void
     */
    private function buildController($filename, $stubpath, $savepath)
    {
        $classname = $this->getControllerName($filename);
        $name = $this->getFileName($filename);
        $stub = file_get_contents($stubpath);
        $controller = str_ireplace(["{classname}", "{name}"], [$classname, $name], $stub);
        // save the file
        $modelname = $classname . ".php";
        file_put_contents($savepath . "/" . $modelname, $controller);
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
