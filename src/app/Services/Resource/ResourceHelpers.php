<?php 

namespace Thinmartiancms\Cms\App\Services\Resource;

use CmsYaml;

trait ResourceHelpers
{
    
    /*
    |--------------------------------------------------------------------------
    | Resource Helpers
    |--------------------------------------------------------------------------
    |
    | Dumping ground for helpers used across all resources. If a method doesn't
    | really fit in a resource, it will likely get put in here.
    |
    */
    
    /**
     * Return the resolved model and also set the property
     * 
     * @return void
     */
    private function setModel()
    {
        // YAML: GET MODEL NAME FROM YAML
        $this->model = app()->make("App\Cms\\" . "CmsUser");
    }
    
    /**
     * Get the record for page value
     * 
     * @return integer
     */
    public function getRecordsPerPage()
    {
        return intval(request()->input("records_per_page", config("cms.cms.records_per_page")));
    }
    
}