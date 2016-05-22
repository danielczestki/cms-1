<?php

use Illuminate\Database\Capsule\Manager as DB;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    
    /**
     * Setup for the DB
     */
    public function setUp()
    {
        $this->setUpDatabase();
        $this->migrateTable();
    }
    
    /**
     * Setup the SQLite DB in memory and boot Eloquent
     */
    protected function setUpDatabase()
    {
        $database = new DB;
        $database->addConnection(["driver" => "sqlite", "database" => ":memory:"]);
        $database->bootEloquent();
        $database->setAsGlobal();
    }
    
    /**
     * Migrate a 100% fake DB table we can run tests against
     * 
     * @return void
     */
    protected function migrateTable()
    {
        DB::schema()->create("cms_resource", function ($table) {
            $table->increments("id");
            $table->string("title", 50);
            $table->string("summary", 2000);
            $table->text("body");
            $table->dateTime("published_at");
            $table->timestamps();  
        });
    }
    
}

/**
 * Stub up a model
 */
class CmsResource extends Illuminate\Database\Eloquent\Model
{
    
}