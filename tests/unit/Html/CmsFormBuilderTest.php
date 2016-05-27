<?php

use Thinmartian\Cms\App\Html\CmsFormBuilder;

class CmsFormBuilderTest extends TestCase
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
        parent::setUp();
        $this->form = new CmsFormBuilder;
    }
    
    /** @test */
    public function it_opens_a_form_with_the_correct_path()
    {
        $html = (string) $this->form->open(["url" => "/path"])->toHtml();
        $this->assertContains("/path", $html);
    }
    
    
}