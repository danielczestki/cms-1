<?php

use Thinmartian\Cms\App\Html\CmsFormBuilder;

function view($path) {
    return "<form>";
}

class CmsFormBuilderTest extends PHPUnit_Framework_TestCase
{
    
    /**
     * @var Thinmartian\Cms\App\Html\CmsFormBuilder
     */
    protected $form;
    
    /**
     * setup
     */
    public function setUp()
    {
        $this->form = new CmsFormBuilder;
    }
    
    /** @test */
    public function it_opens_a_form()
    {
        $html = $this->form->open(["url" => "/path"])->toHtml();
        $this->assertContains("form", $html);
    }
    
    
}