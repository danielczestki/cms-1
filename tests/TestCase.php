<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://cms.keogh.site';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../../../../bootstrap/app.php';
        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        return $app;
    }
    
    /**
     * Migrate the DB from the esc package migrations and rollback on destroy
     */
    protected function migrate()
    {
        $this->artisan('migrate', [
            '--path' => 'packages/thinmartian/cms/src/database/migrations'
        ]);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');
        });
    }
}
