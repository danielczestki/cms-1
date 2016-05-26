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
     * Update the resource from the edit form
     * 
     * @param  ResourceInput $input The form field
     * @return void
     */
    public function updateResource($resource, ResourceInput $input)
    {
        $form = $input->getInput();
        foreach ($resource->getFillable() as $column) {
            if (array_key_exists($column, $form)) $resource->$column = $form[$column];
        }
        $resource->save();
        return $resource;
    }
    
    /**
     * Delete the resource(s)
     * 
     * @param  array $ids List of ids to delete (could be 1, could 10000000)
     * @return void
     */
    public function deleteResources($ids = [])
    {
        if (! $ids) return false;
        return $this->model->destroy($ids);
    }
    
    /**
     * Return the resource
     * 
     * @param  integer $id
     * @return Illuminate\Database\Eloquent\Model
     */
    public function getResource($id = null)
    {
        if (! $id) return app()->abort(400, "Invalid ID");
        if (! $model = $this->model->find($id))  return app()->abort(404, "Resource not found");
        return $model;
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