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
     * Simple flag to know if a migration was generated for the console response
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
     * Path to the migrations files
     * 
     * @var string
     */
    protected $migrationsPath;
    
    /**
     * Path to the stub file
     * 
     * @var string
     */
    protected $stubPath;
    
    /**
     * Date format for the cms migrations file
     */
    const MIGRATIONDATE = "Y_m_d";
    
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
        $this->stubPath = realpath(__DIR__ . "/stubs/migration.stub");
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $number = 0;
        foreach ($this->getYamlFiles() as $filename) {
            // move on if the file exists, we don't want to overwrite
            if (! $this->migrationExists($filename)) {
                // it's not there, so we need to build it
                $migration = $this->buildMigration($filename, $number);
                $number++;
            }
        }
        if ($this->created) {
            $this->info("Migrations generated from YAML definitions successfully!");
        } else {
            $this->comment("No migrations to generate");            
        }
        
    }
    
    
    //
    // MIGRATION
    // 
    
    /**
     * Build the migration file
     * 
     * @param  string  $filename
     * @param  integer $number
     * @return void
     */
    private function buildMigration($filename, $number)
    {
        // setup
        $fullpath = $this->getFullYamlPath($filename);
        $yaml = $this->yaml->parse(file_get_contents($fullpath));
        // build
        $stub = file_get_contents($this->stubPath);
        $classname = $this->buildClassname($filename);
        $tablename = $this->getFileTablename($this->getFilename($filename));
        $schema = $this->buildSchema($yaml);
        $migration = str_ireplace(["{classname}", "{tablename}", "{schema}"], [$classname, $tablename, $schema], $stub);
        // save the file
        $migrationname = "{$this->getFileDate()}_{$this->getFileNumber($number)}_{$this->getFilePrefix()}{$tablename}{$this->getFileSuffix()}";
        file_put_contents($this->migrationsPath . "/" . $migrationname, $migration);
        $this->created = true;
    }
    
    /**
     * Build the schema for the migration file
     * 
     * @param  Symfony\Component\Yaml\Parser $yaml
     * @return string
     */
    private function buildSchema($yaml)
    {
        $result = "";
        $fields = $yaml["fields"];
        foreach ($fields as $column => $data) {
            $result .= $this->buildColumn($column, $data);
        }
        return $result;
    }
    
    /**
     * Determine the column type and return the string
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildColumn($column, $data)
    {
        $str = '$table->';
        switch ($data["type"]) {
            case "text" :
            case "select" :
            case "email" :
            case "password" :
                $str .= $this->buildText($column, $data);
            break;
            case "textarea" :
                $str .= $this->buildTextarea($column, $data);
            break;
            case "wysiwyg" :
                $str .= $this->buildWysiwyg($column, $data);
            break;
            case "checkbox" :
            case "radio" :
                $str .= $this->buildCheckbox($column, $data, "options");
            break;
            case "datetime" :
                $str .= $this->buildDatetime($column, $data);
            break;
            case "date" :
                $str .= $this->buildDate($column, $data);
            break;
            case "number" :
                $str .= $this->buildNumber($column, $data);
            break;
        }
        return "            " . $str . $this->buildNullable($data) . ";\n";
    }
    
    /**
     * Return a "text" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildText($column, $data)
    {
        return 'string("'. $column .'", '. $this->buildLength($data) .')';
    }
    
    /**
     * Return a "textarea" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildTextarea($column, $data)
    {
        return 'string("'. $column .'", '. $this->buildLength($data, 4000) .')';
    }
    
    /**
     * Return a "wysiwyg" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildWysiwyg($column, $data)
    {
        return 'text("'. $column .'")';
    }
    
    /**
     * Return a "checkbox" migration
     * 
     * @param  string  $column      The column name
     * @param  array   $data        The data from the yaml
     * @param  string  $checkfor    Check for this key to determine if we are boo or string
     * @return string
     */
    private function buildCheckbox($column, $data, $checkfor = "value")
    {
        if (array_key_exists($checkfor, $data)) {
            // assume it's not boolean
            return $this->buildText($column, $data);
        } else {
            // it should be boolean
            return $this->buildBoolean($column, $data);
        }
    }
    
    /**
     * Return a "boolean" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildBoolean($column, $data)
    {
        return 'boolean("'. $column .'")';
    }
    
    /**
     * Return a "datetime" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildDatetime($column, $data)
    {
        return 'dateTime("'. $column .'")';
    }
    
    /**
     * Return a "date" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildDate($column, $data)
    {
        return 'date("'. $column .'")';
    }
    
    /**
     * Return a "number" migration
     * 
     * @param  string $column The column name
     * @param  array  $data   The data from the yaml
     * @return string
     */
    private function buildNumber($column, $data)
    {
        return 'integer("'. $column .'")->unsigned()';
    }
    
    /**
     * Is this field required or not?
     * 
     * @param  array $data The data from the yaml
     * @return string
     */
    private function buildNullable($data)
    {
        if (! array_key_exists("validationOnCreate", $data)) return '->nullable()';
        return in_array("required", explode("|", $data["validationOnCreate"])) ? null : '->nullable()';
    }
    
    /**
     * Return a max length based on the validation rules
     * 
     * @param  array $data      The data from the yaml
     * @param  integer $default The default length if one don't exist
     * @return integer
     */
    private function buildLength($data, $default = 255)
    {
        if (! array_key_exists("validationOnCreate", $data)) return $default;
        $rules = $data["validationOnCreate"];
        
        foreach (explode("|", $rules) as $rule) {
            if (starts_with($rule, "max")) {
                return intval(str_ireplace("max:", "", $rule));
            }
        }
        return $default;
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
     * @param integer $number
     * @return integer
     */
    private function getFileNumber($number)
    {
        return intval(date("U")) + $number;
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
