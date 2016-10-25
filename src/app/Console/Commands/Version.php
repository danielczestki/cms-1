<?php

namespace Thinmartian\Cms\App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Yaml\Parser;

class Version extends Commands
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cms:version';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the currently installed Thin Martian CMS version number';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("<info>Thin Martian CMS</info> version <comment>". CMSVERSION ."</comment>");
    }
}
