<?php 

namespace Thinmartian\Cms\App\Services\Resource;

use DB;
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
        $result = $this->model;
        $result = $this->gridSearch($result);
        return $result->paginate($this->getRecordsPerPage());        
    }
    
    /**
     * Build the search query for the grid
     * 
     * @param  Illuminate\Database\Collection $result
     * @return Illuminate\Database\Collection
     */
    private function gridSearch($result)
    {
        if (! $search = $this->getSearch()) return $result;
        
        $searchable = CmsYaml::getSearchable();
        return $result->where(function($q) use ($searchable, $search) {
            foreach ($searchable as $column) {
                $q->orWhere(DB::raw($column), "like", "%". $search ."%");
            }
        });
    }
   
   
   
    
}