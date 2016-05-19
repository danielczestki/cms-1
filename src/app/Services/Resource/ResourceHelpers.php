<?php 

namespace Thinmartiancms\Cms\App\Services\Resource;

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
     * @return Illuminate\Database\Eloquent\Model
     */
    private function getModel()
    {
        return $this->model = app()->make("App\Cms\\" . $this->modelName);
    }
    
    /**
     * Get the record por page value
     * 
     * @return integer
     */
    public function getRecordsPerPage()
    {
        return intval(request()->input("records_per_page", config("cms.cms.records_per_page")));
    }
    
}