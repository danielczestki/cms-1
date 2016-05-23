<?php 

namespace Thinmartian\Cms\App\Services\Resource;
    
/*
|--------------------------------------------------------------------------
| Resource Helpers
|--------------------------------------------------------------------------
|
| Dumping ground for helpers used across all resources. If a method doesn't
| really fit in a resource, it will likely get put in here.
|
*/

use CmsYaml;

trait ResourceHelpers
{
    
    use Redirects;
    
    /**
     * Fetch the meta from the Yaml file
     * 
     * @return array
     */
    public function getMeta()
    {
        return CmsYaml::getMeta();
    }
    
    /**
     * Return the array of filters for numerous links/redirects across the resource
     * 
     * @return array
     */
    public function getFilters()
    {
        return request()->only($this->filters);
    }
    
    /**
     * Get the record for page value
     * 
     * @return integer
     */
    public function getRecordsPerPage()
    {
        $default = array_key_exists("records_per_page", $this->getMeta()) ? $this->getMeta()["records_per_page"] : config("cms.cms.records_per_page");
        return intval(request()->get("records_per_page", $default));
    }
    
    /**
     * Return the resolved model and also set the property
     * 
     * @return void
     */
    private function setModel()
    {
        $model = "Cms" . trim(ucfirst(str_singular($this->name)));
        $this->model = app()->make("App\Cms\\$model");
    }
}