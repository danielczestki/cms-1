<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\DatabaseManager;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Filesystem\Filesystem;
use Thinmartian\Cms\App\Services\Cms;
use Storage;

class Destroy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:destroy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all CMS data and start again. <error>caution: will delete all CMS data incl database!</error>';
    
    /**
     * The src/ path to the installed package
     * 
     * @var string
     */
    protected $srcpath;
    
    /**
     * @var Illuminate\Contracts\Console\Kernel
     */
    protected $artisan;
    
    /**
     * @var Illuminate\Database\DatabaseManager
     */
    protected $db;
    
    /**
     * @var Symfony\Component\Finder\Finder
     */
    protected $finder;
    
    /**
     * @var Symfony\Component\Filesystem\Filesystem
     */
    protected $filesystem;
    
    /**
     * @var Thinmartian\Cms\App\Services\Cms
     */
    protected $cms;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Kernel $artisan, DatabaseManager $db, Finder $finder, Filesystem $filesystem, Cms $cms)
    {
        parent::__construct();
        $this->srcpath = realpath(__DIR__ . "/../../../");
        $this->artisan = $artisan;
        $this->db = $db;
        $this->finder = $finder;
        $this->filesystem = $filesystem;
        $this->cms = $cms;
    }

    /**
     * Execute the console command. We add little sleeps inbetweeen tasks here to
     * let the user see what's happening, it's unreadable otherwise
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->confirm("You are about to delete all CMS data, drop database tables and delete all custom YAML definitions. Are you sure? <error>This is irreversible!</error>")) {
            $this->info("Destroying CMS, please wait...");
            // Delete the App/Cms folder
            $this->destroyCmsFolder();
            //  Delete the media folder
            $this->destroyMediaFolder();
            //  Delete the public asset vendor folder
            $this->destroyAssetsFolder();
            //  Delete the public config folder
            $this->destroyConfigFolder();
            //  Delete all models
            $this->destroyModels();
            //  Delete all controllers
            $this->destroyControllers();
            //  Delete all migrations
            $this->destroyMigrations();
            //  Drop DB tables and clean migrations table
            $this->destroyDatabase();
            $this->comment("CMS data reset. Run <question>php artisan cms:build</question> to rebuild.");
        } else { $this->comment("Phew! That was close!"); }
    }
    
    /**
     * Deletes the App/Cms folder
     * 
     * @return void
     */
    protected function destroyCmsFolder()
    {
        $this->filesystem->remove(app_path("Cms"));
    }
    
    /**
     * Deletes the media folder
     * 
     * @return void
     */
    protected function destroyMediaFolder()
    {
        $this->filesystem->remove(config("filesystems.disks.local.root", storage_path("app")) . "/cms");
        $this->filesystem->remove(storage_path("app/public/cms"));
        Storage::disk(config("cms.cms.media_disk"))->deleteDirectory(config("cms.cms.media_path"));
    }
    
    /**
     * Deletes the public assets folder
     * 
     * @return void
     */
    protected function destroyAssetsFolder()
    {
        $this->filesystem->remove(public_path("vendor/cms"));
    }
    
    /**
     * Deletes the public config folder
     * 
     * @return void
     */
    protected function destroyConfigFolder()
    {
        $this->filesystem->remove(config_path("cms"));
    }
        
    /**
     * Deletes the Package/Models
     * 
     * @return void
     */
    protected function destroyModels()
    {
        $core = $this->srcpath . "/app/Models/Core";
        $coreProtected = $this->cms->getProtectedModels();
        // core
        foreach($this->finder->create()->files()->in($core) as $filepath => $file) {
            if (! $this->ignore($coreProtected, $file)) {
                $this->filesystem->remove($filepath);
            }
        }
    }
    
    /**
     * Deletes the Package/Controllers
     * 
     * @return void
     */
    protected function destroyControllers()
    {
        $core = $this->srcpath . "/app/Http/Controllers/Core";
        $coreProtected = $this->cms->getProtectedControllers();
        $customProtected = $this->cms->getProtectedFiles();
        // core
        foreach($this->finder->create()->files()->in($core) as $filepath => $file) {
            if (! $this->ignore($coreProtected, $file)) {
                $this->filesystem->remove($filepath);
            }
        }
    }
    
    /**
     * Deletes all migrations files
     * 
     * @return void
     */
    protected function destroyMigrations()
    {
        //$package = $this->srcpath . "/database/migrations";
        $app = database_path("migrations");
        $packageProtected = $this->cms->getProtectedMigrations();
        // package
        /*foreach($this->finder->create()->files()->in($package) as $filepath => $file) {
            if (! $this->ignore($packageProtected, $file)) {
                $this->filesystem->remove($filepath);
            }
        }*/
        // app (different as we do a match as we have no idea whats in here)
        foreach($this->finder->create()->files()->in($app) as $filepath => $file) {
            if (str_contains($file->getFilename(), "_cms_")) {
               $this->filesystem->remove($filepath);
            }
        }
    }
    
    /**
     * Drop DB table and cleans the migration table
     * 
     * @return void
     */
    protected function destroyDatabase()
    {
        // clean the migrations table first by removing the cms tables
        foreach ($this->db->table("migrations")->get() as $row) {
            if (str_contains($row->migration, "_cms_")) {
                $this->db->table("migrations")->where("migration", $row->migration)->delete();
            }
        }
        // drop tables
        foreach ($this->db->select("show tables") as $row) {
            if (starts_with(head($row), "cms_")) {
                $this->db->select("drop table " . head($row));
            }
        }
    }
    
    /**
     * Ignore these files
     * 
     * @param  array  $files
     * @param  string $file
     * @return boolean
     */
    private function ignore($files, $file)
    {
        return in_array($file->getFilename(), $files);
    }
    
}