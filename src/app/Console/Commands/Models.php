<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class Models extends Commands
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build models from installed YAML config files. <comment>(does not copy to the app/Cms directory)</comment>';
    
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
     * Prefix for the models
     */
    const MODELPREFIX = "Cms";
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->corePath = realpath(__DIR__ . "/../../Models/Core/");
        $this->customPath = app_path("Cms");
        $this->stubCorePath = realpath(__DIR__ . "/stubs/model_core.stub");
        $this->stubCustomPath = realpath(__DIR__ . "/stubs/model_custom.stub");
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
        $this->info("Models generated from YAML definitions successfully!");
    }
    
    /**
     * Build the core model
     * 
     * @param  string $filename
     * @return void
     */
    private function core($filename)
    {
        $this->buildModel("core", $filename, $this->stubCorePath, $this->corePath);
    }
    
    /**
     * Build the custom model
     * 
     * @param  string $filename
     * @return void
     */
    private function custom($filename)
    {
        $this->buildModel("custom", $filename, $this->stubCustomPath, $this->customPath);
    }
    
    /**
     * Build the model
     * 
     * @param  string $type
     * @param  string $filename
     * @param  string $stubpath
     * @param  string $savepath
     * @return void
     */
    private function buildModel($type, $filename, $stubpath, $savepath)
    {
        $classname = $this->getModelName($filename);
        $modelname = $classname . ".php";
        // if the model is protected do not add/edit/overwrite/delete... don't touch it hear me!
        if ($type == "core" and in_array($modelname, $this->cms->getProtectedModels(false))) return;
        // we are good to write
        $stub = file_get_contents($stubpath);
        $yaml = $this->getFilename($filename);
        $tablename = $this->getTablename($yaml);
        $model = str_ireplace(["{classname}", "{yaml}", "{tablename}"], [$classname, $yaml, $tablename], $stub);
        // save the file
        file_put_contents($savepath . "/" . $modelname, $model);
    }
    
    /**
     * Get the model name
     * 
     * @param  string $filename
     * @return string
     */
    private function getModelName($filename)
    {
        return self::MODELPREFIX . trim(ucfirst(str_singular($this->getFileName($filename))));
    }
    
}
