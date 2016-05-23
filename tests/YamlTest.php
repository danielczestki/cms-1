<?php

use Thinmartian\Cms\App\Services\Definitions\Yaml as CmsYaml;

function app_path() {
    return __DIR__ . "/";
}

class YamlTest extends TestCase
{
    
    /**
     * @var Thinmartian\Cms\App\Services\Definitions\Yaml
     */
    protected $yaml;
    
    public function setUp()
    {
        $this->yaml = new CmsYaml;
        $this->yaml->setFile("Resources");
    }
    
    /** @test */
    public function meta_is_array()
    {
        $meta = $this->yaml->getMeta();
        $this->assertTrue(is_array($meta));
    }
    
    /** @test */
    public function meta_has_title_attribute()
    {
        $meta = $this->yaml->getMeta();
        $this->assertArrayHasKey("title", $meta);
    }
    
    // Listing
    
    /** @test */
    public function listing_is_array()
    {
        $listing = $this->yaml->getListing();
        $this->assertTrue(is_array($listing));
    }
    
    /** @test */
    public function listing_has_id_attribute()
    {
        $listing = $this->yaml->getListing();
        $this->assertArrayHasKey("id", $listing);
    }
    
}