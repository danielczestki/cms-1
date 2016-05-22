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
    public function meta_is_instance_of_stdclass()
    {
        $meta = $this->yaml->getMeta();
        $this->assertInstanceOf("stdClass", $meta);
    }
    
    /** @test */
    public function meta_has_title_attribute()
    {
        $meta = $this->yaml->getMeta();
        $this->assertTrue(property_exists($meta, "title"));
    }
    
    // Listing
    
    /** @test */
    public function listing_is_instance_of_stdclass()
    {
        $listing = $this->yaml->getListing();
        $this->assertInstanceOf("stdClass", $listing);
    }
    
    /** @test */
    public function listing_has_id_attribute()
    {
        $listing = $this->yaml->getListing();
        $this->assertTrue(property_exists($listing, "id"));
    }
    
}