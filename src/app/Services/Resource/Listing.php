<?php 

namespace Thinmartian\Cms\App\Services\Resource;

use CmsYaml;

/*
|--------------------------------------------------------------------------
| Read/List Trait
|--------------------------------------------------------------------------
|
| This trait controls all aspect of Reading data from the persistence layer.
| This includes (but not limited to) listing out the resources on the index
| page to showing the record(s) on edit/show
|
*/

trait Listing
{
    
    use ResourceHelpers;
    
    /**
     * Get the listing columns used on the grid
     * 
     * @return  Array
     */
    public function getListColumns()
    {
        return CmsYaml::getListing();
    }
    
    /**
     * Output the list of resources for the index page
     * 
     * @return Illuminate\Pagination\LengthAwarePaginator
     */
    public function grid()
    {
        return $this->model->paginate($this->getRecordsPerPage());
    }
   
   
    
}