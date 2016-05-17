<?php

namespace Thinmartiancms\Cms\App\Html;

class CmsFormBuilder {
    
    /**
     * Create a new cms form builder instance.
     * 
     * @return void
     */
    public function __construct()
    {
        
    }
    
    public function hello() {
        return view("cms::html.form.input");
    }
    

}