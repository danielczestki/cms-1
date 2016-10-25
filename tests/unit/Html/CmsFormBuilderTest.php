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
    public function it_opens_a_form()
    {
        $html = (string) $this->form->open(["url" => "/path"])->toHtml();
        $this->assertContains("/path", $html);
    }
    
    /** @test */
    public function it_closes_a_form()
    {
        $html = (string) $this->form->close()->toHtml();
        $this->assertEquals("</form>", $html);
    }
}
