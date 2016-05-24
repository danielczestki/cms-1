<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\Kernel;

class Build extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compile all YAML definitions and build the CMS';
    
    /**
     * @var Illuminate\Contracts\Console\Kernel
     */
    protected $artisan;
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Kernel $artisan)
    {
        parent::__construct();
        $this->artisan = $artisan;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->comment("Generating database migrations from YAML definitions...");
        $this->artisan->call("cms:migrations");
        $this->info("Database migrations generated successfully!");
        
        $this->comment("Publishing core files...");
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider"
        ]);
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider",
            "--tag" => ["assets"],
            "--force" => true
        ]);
        $this->info("Core files published successfully!");
        //$this->artisan->call("cms:migrations");      
    }
    
}
