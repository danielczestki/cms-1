<?php 

namespace Thinmartiancms\Cms\App\Services\Resource;

trait Listing
{
    
    use ResourceHelpers;
    
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