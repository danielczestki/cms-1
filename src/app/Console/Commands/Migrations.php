<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class Migrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:migrations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Build migrations from installed YAML config files';
    
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
     * Path to the migrations files
     * 
     * @var string
     */
    protected $migrationsPath;
    
    /**
     * Path to the stubs
     * 
     * @var string
     */
    protected $stubsPath;
    
    /**
     * Date format for the cms migrations file
     */
    const MIGRATIONDATE = "Y_m_d";
    
    /**
     * Number step for the migration file (sits after the date)
     * 
     * @example 2016_12_25_100000
     */
    const MIGRATIONNUMBER = 100000;
    
    /**
     * Prefix for the cms migrations file
     */
    const MIGRATIONPREFIX = "create";
    
    /**
     * Suffix for the cms migrations file
     */
    const MIGRATIONSUFFIX = "table.php";
    
    /**
     * Prefix for the cms tables
     */
    const TABLEPREFIX = "cms";
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Parser $yaml)
    {
        parent::__construct();
        $this->yaml = $yaml;
        $this->yamlPath = app_path("Cms/Definitions/");
        $this->migrationsPath = realpath(__DIR__ . "/../../../database/migrations/");
        $this->stubsPath = realpath(__DIR__ . "/stubs/");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach ($this->getYamlFiles() as $filename) {
            // move on if the file exists, we don't want to overwrite
            if ($this->migrationExists($filename)) continue;
            // it's not there, so we need to build it
            $migration = $this->buildMigration($filename);
            
            dd($migration);
        }
    }
    
    
    //
    // MIGRATION
    // 
    
    private function buildMigration($filename)
    {
        // setup
        $fullpath = $this->getFullYamlPath($filename);
        $yaml = $this->yaml->parse(file_get_contents($fullpath));
        // build
        $classname = $this->buildClassname($filename);
        $tablename = $this->getFileTablename($this->getFilename($filename));
        $schema = $this->buildSchema($yaml);
        
        dd($schema);
        
        
    }
    
    /**
     * Build the schema for the migration file
     * 
     * @param  Symfony\Component\Yaml\Parser $yaml
     * @return string
     */
    private function buildSchema($yaml)
    {
        $fields = $yaml["fields"];
        dd($fields);
    }
    
    /**
     * Return the class name for the migration file
     * 
     * @param  string $filename
     * @return string
     */
    private function buildClassname($filename)
    {
        return ucfirst(self::TABLEPREFIX) . ucfirst(str_plural($this->getFilename($filename)));
    }
    
    
    
    //
    // GETTERS
    // 
       
    /**
     * Get the full path to the yaml file
     * 
     * @param  string $filename
     * @return string
     */
    private function getFullYamlPath($filename)
    {
        return $this->yamlPath . $filename;
    }
     
    /**
     * Get the filename from the yaml config to be used for the table
     * 
     * @param  string $filename
     * @return string
     */
    private function getFilename($filename)
    {
        return pathinfo($filename)["filename"];
    }
    
    /**
     * Get the date part for the migration file
     * 
     * @return string
     */
    private function getFileDate()
    {
        return date(self::MIGRATIONDATE);
    }
    
    /**
     * Get the number part for the migration file
     * 
     * @return integer
     */
    private function getFileNumber()
    {
        return self::MIGRATIONNUMBER;
    }
    
    /**
     * Get the tablename part for the migration file (create_cms_*)
     * 
     * @param  string $name The yaml config filename
     * @return string
     */
    private function getFileTablename($name)
    {
        return self::TABLEPREFIX . "_" . trim(strtolower(str_plural($name)));
    }
    
    /**
     * Return the prefix part of the migration file
     * 
     * @return string
     */
    private function getFilePrefix()
    {
        return self::MIGRATIONPREFIX . "_";
    }
    
    /**
     * Return the suffix part of the migration file
     * 
     * @return string
     */
    private function getFileSuffix()
    {
        return "_" . self::MIGRATIONSUFFIX;
    }
    
    //
    // UTILS
    // 
    
    /**
     * Check if the migration file is already there
     * 
     * @param  string $filename
     * @return string
     */
    private function migrationExists($filename)
    {
        $file = $this->getFilePrefix() . $this->getFileTablename($this->getFilename($filename)) . $this->getFileSuffix();
        foreach (scandir($this->migrationsPath) as $filename) {
            if (str_contains($filename, $file)) return true;
        }
        return false;
    }
    
    /**
     * Return all yhe yaml config files in the definitions directory
     * 
     * @return array
     */
    private function getYamlFiles()
    {
        $arr = [];
        foreach (scandir($this->yamlPath) as $filename) {
            if (ends_with($filename, ".yaml")) $arr[] = $filename;
        }
        return $arr;
    }
    
    
    
    
    /**
     * Get all the valid .yaml files from the config path
     * @return array
     */
    // private function yamls()
    // {
    //     $arr = [];
    //     foreach (scandir($this->path) as $filename) {
    //         if (ends_with($filename, ".yaml")) $arr[] = $filename;
    //     }
    //     return $arr;
    // }
    
    // private function exists($filename)
    // {
    //     $table = $this->filenameTable(pathinfo($this->fullpath($filename))["filename"]);
    //     $searchable = "{$prefix}_{$tableprefix}_{$table}_{$suffix}";
    //     dd($searchable);
    //     return false;
    // }
    
    /**
     * Generate the migration filename
     * 
     * @param  string $name The name of the YAML config file (this is used for the table name)
     * @return string
     */
    // private function filename($name)
    // {
    //     $date = date(self::MIGRATIONDATE);
    //     $number = self::MIGRATIONNUMBER;
    //     $prefix = self::MIGRATIONPREFIX;
    //     $tableprefix = self::TABLEPREFIX;
    //     $table = $this->filenameTable();
    //     $suffix = self::MIGRATIONSUFFIX;
    //     return "{$date}_{$number}_{$prefix}_{$tableprefix}_{$table}_{$suffix}";
    // }
    
    /**
     * Return the parse table name for the file
     * 
     * @param  string $name The name of the YAML config file
     * @return string
     */
    // private function filenameTable($name)
    // {
    //     return trim(strtolower(str_plural($name)));
    // }
    
    /**
     * Return the full path to the file
     * 
     * @param  string $filename The file name
     * @return string
     */
    // private function fullpath($filename)
    // {
    //     return $this->path . $filename;
    // }
    
}
