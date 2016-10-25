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
        // does this object have the 'published' column enabled?
        if (method_exists($result, 'withDrafts')) {
            $result = $result->withDrafts();
        }
        $result = $this->gridSelect($result);
        $result = $this->gridSearch($result);
        return $result->orderBy($this->getSort(), request()->get("sort_dir", config("cms.cms.default_sort_direction")))->paginate($this->getRecordsPerPage());
    }
    
    /**
     * Return the sort value and ensure its valid
     *
     * @return string
     */
    private function getSort()
    {
        $sortValue = request()->get("sort") ?: config("cms.cms.default_sort_column");
        if (array_key_exists($sortValue, CmsYaml::getListing())) {
            return $sortValue;
        } else {
            request()->replace(["sort", config("cms.cms.default_sort_column")]); // replace the request so the user knows we are sorting by id
            return config("cms.cms.default_sort_column");
        }
    }
    
    /**
     * Return the select list
     *
     * @param  Illuminate\Database\Collection $result
     * @return Illuminate\Database\Collection
     */
    private function gridSelect($result)
    {
        $listing = CmsYaml::getListing();
        foreach ($listing as $idx => $data) {
            $selects[] = isset($data["column"]) ? \DB::raw($data["column"] . " as $idx") : $idx;
        }
        return $result->select(...$selects);
    }
    
    
    /**
     * Build the search query for the grid
     *
     * @param  Illuminate\Database\Collection $result
     * @return Illuminate\Database\Collection
     */
    private function gridSearch($result)
    {
        if (! $search = $this->getSearch()) {
            return $result;
        }
        
        $searchable = CmsYaml::getSearchable();
        return $result->where(function ($q) use ($searchable, $search) {
            foreach ($searchable as $column) {
                $q->orWhere(DB::raw($column), "like", "%". $search ."%");
            }
        });
    }
}
