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
     * Execute the console command. We add little sleeps inbetweeen tasks here to
     * let the user see what's happening, it's unreadable otherwise
     *
     * @return mixed
     */
    public function handle()
    {
        $bar = $this->setupBar();
        
        $bar->setMessage("<comment>Copying YAML definitions to app...</comment>");
        $bar->advance();
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider",
            "--tag" => ["definitions"]
        ]);
        usleep(400000);
        
        $bar->setMessage("<comment>Generating database migrations from YAML definitions...</comment>");
        $bar->advance();
        $this->artisan->call("cms:migrations");
        usleep(400000);
        
        $bar->setMessage("<comment>Publishing core files...</comment>");
        $bar->advance();
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider"
        ]);
        usleep(400000);
        
        $bar->setMessage("<comment>Ensuring public assets are up-to-date...</comment>");
        $bar->advance();
        $this->artisan->call("vendor:publish", [
            "--provider" => "Thinmartian\\Cms\\CmsServiceProvider",
            "--tag" => ["assets"],
            "--force" => true
        ]);
        usleep(400000);
        
        $bar->setMessage("<comment>Migrating database...</comment>");
        $bar->advance();
        $this->artisan->call("migrate");
        usleep(100000);
        
        $bar->setMessage("<info>CMS build complete</info>");
        $bar->finish();
    }
    
    /**
     * Return a progress bar
     * 
     * @return Symfony\Component\Console\Helper\ProgressBar
     */
    private function setupBar($count = 5)
    {
        $bar = $this->output->createProgressBar($count);
        $bar->setFormat("%message% (%current%/%max%)\n%bar% %percent:3s%%\n");
        $bar->setBarWidth(50);
        return $bar;
    }
    
}
