<?php 

namespace Thinmartian\Cms\App\Services\Resource;

use CmsYaml;

/*
|--------------------------------------------------------------------------
| Form Trait
|--------------------------------------------------------------------------
|
| This trait controls all aspects of the forms for a resource. This includes
| the add and edit form but does not control deletes
|
*/

trait Form
{
    
    /**
     * Create the resource from the create form
     * 
     * @param  ResourceInput $input The form field
     * @return void
     */
    public function createResource(ResourceInput $input)
    {
        $form = $input->getInput();
        $resource = $this->model->create($form);
        return $resource;
    }
    
    /**
     * Get the form fields for the resource
     * 
     * @return array
     */
    public function getFields()
    {
        // add a name key, to make cmsform api more simple
        $arr = array_map(function($data, $idx) {
            $data["name"] = $idx;
            return $data;
        }, CmsYaml::getFields(), array_keys(CmsYaml::getFields()));
        return $arr;
    }   
   
    
}