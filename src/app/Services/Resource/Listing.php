<?php 

namespace Thinmartian\Cms\App\Services\Resource;

use CmsYaml;

/*
|--------------------------------------------------------------------------
| Listing Trait
|--------------------------------------------------------------------------
|
| This trait controls all aspect of listing data from the persistence layer.
| This includes (but not limited to) listing out the resources on the index
| page
|
*/

trait Listing
{
    
    /**
     * Get the listing columns used on the grid
     * 
     * @return  array
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