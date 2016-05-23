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