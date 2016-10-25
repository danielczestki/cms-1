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
    protected $signature = 'cms:models {--overwrite}';

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
        $this->corePath = app_path("Cms/System");
        $this->customPath = app_path("Cms");
        $this->stubCorePath = realpath(__DIR__ . "/stubs/model_core.stub");
        $this->stubCustomPath = realpath(__DIR__ . "/stubs/model_custom.stub");
        $this->hasOnePath = realpath(__DIR__ . "/stubs/relation_hasone.stub");
        $this->hasManyPath = realpath(__DIR__ . "/stubs/relation_hasmany.stub");
        $this->hasManyThroughPath = realpath(__DIR__ . "/stubs/relation_hasmanythrough.stub");
        $this->belongsToPath = realpath(__DIR__ . "/stubs/relation_belongsto.stub");
        $this->belongsToManyPath = realpath(__DIR__ . "/stubs/relation_belongstomany.stub");
        $this->stubMorphToManyPath = realpath(__DIR__ . "/stubs/relation_morphtomany.stub");
        $this->stubMorphedByManyPath = realpath(__DIR__ . "/stubs/relation_morphedbymany.stub");
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
        if ($type == "core" and in_array($modelname, $this->cms->getProtectedModels(false))) {
            return;
        }
        // deal with relations
        $yamlFileName = $this->getFilename($filename);
        $tablename = $this->getTablename($yamlFileName);
        $fullpath = $this->getFullYamlPath($filename);
        $yaml = $this->yaml->parse(file_get_contents($fullpath));
        $relations  = $this->buildRelations($yaml, $filename);
        $versioning = isset($yaml['meta']['version']) && $yaml['meta']['version'] ? 'use \Mpociot\Versionable\VersionableTrait;'.PHP_EOL : false;

        // we are good to write
        $stub = file_get_contents($stubpath);
        $model = str_ireplace(["{classname}", "{yaml}", "{tablename}", "{relations}", "{versionable}"], [$classname, $yamlFileName, $tablename, $relations, $versioning], $stub);

        $saveTo = $savepath . "/" . $modelname;
        if (!$this->option('overwrite') && $type === "custom" && file_exists($saveTo)) {
            $this->comment("$classname already exists, skipping");
            return;
        }
        // save the file
        file_put_contents($saveTo, $model);
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

    /**
     * Build the relations + inverse relations
     *
     * @param  Symfony\Component\Yaml\Parser $yaml
     * @return string
     */
    private function buildRelations($yaml, $filename)
    {
        $result = [];
        // were any relations set?
        $relations = isset($yaml["relations"]) ? $yaml["relations"] : [];
        // loop
        foreach ($relations as $relation => $attributes) {
            // does a type attr exist? is a file for this type set? does the file exist?
            if (isset($attributes['type']) && isset($this->{$attributes['type'] . 'Path'}) && file_exists($this->{$attributes['type'] . 'Path'})) {
                // get the contents of stub
                $stub = file_get_contents($this->{$attributes['type'] . 'Path'});
                // generate a function name
                $function = strtolower($relation);
                // model name
                $model = 'App\Cms\Cms' . ucwords($relation);
                // table name
                $table = strtolower($relation) . 'able';
                // generate string from stub
                $result[$function] = str_ireplace(["{function}", "{model}", "{table}"], [$function, $model, $table], $stub);
            }
        }
        // deal with any inverse relations - make a map of which functions have inverse ones
        $inverseRelationsMap = ['hasOne'        => 'belongsTo',
                                'hasMany'       => 'belongsTo',
                                'belongsToMany' => 'belongsToMany',
                                'morphToMany'   => 'morphedByMany',
                                ];
        // loop through all yaml files
        foreach ($this->getYamlFiles() as $file) {
            // look in every file other than the one we are currently creating
            if ($filename !== $file) {
                // get the path to the yaml file
                $fullpath = $this->getFullYamlPath($file);
                // parse the yaml
                $yaml = $this->yaml->parse(file_get_contents($fullpath));
                // strip the '.yaml' and make filename lowercase
                $thisClass = preg_replace('/\\.[^.\\s]{3,4}$/', '', strtolower($filename));
                // if any relations have been set
                if (isset($yaml["relations"]) && count($yaml["relations"])) {
                    // loop through them
                    foreach ($yaml["relations"] as $relation => $attributes) {
                        // does the current relation match the class we are creating?
                        if ($relation == $thisClass) {
                            if (isset($attributes['type'])
                            &&
                            array_key_exists($attributes['type'], $inverseRelationsMap)
                            &&
                            isset($this->{$inverseRelationsMap[$attributes['type']] . 'Path'})
                            &&
                            file_exists($this->{$inverseRelationsMap[$attributes['type']] . 'Path'})) {
                                // get the contents of stub
                                $stub = file_get_contents($this->{$inverseRelationsMap[$attributes['type']] . 'Path'});
                                $relation = preg_replace('/\\.[^.\\s]{3,4}$/', '', strtolower($file));
                                $function = strtolower($relation);
                                $model = ucwords($relation);
                                $table = strtolower($relation) . 'able';
                                $result[$function] = str_ireplace(["{function}", "{model}", "{table}"], [$function, $model, $table], $stub);
                            }
                        }
                    }
                }
            }
        }
        return count($result) ? implode($result, PHP_EOL) : null;
    }
}
