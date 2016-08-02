<?php 

namespace Thinmartian\Cms\App\Services\Resource;

use CmsYaml, DB;

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
        $fillable = $this->model->getFillable();
        foreach ($fillable as $column) {
            if (array_key_exists($column, $form)) $this->model->$column = is_array($form[$column]) ? null : $form[$column];
        }
        $this->special($this->model);
        $this->model->save();
        // media
        $this->saveMedia($this->model);
        return $this->model;
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
            if (array_key_exists($column, $form)) $resource->$column = is_array($form[$column]) ? null : $form[$column];
        }
        $this->special($resource);
        $resource->save();
        // media
        $this->saveMedia($resource);
        return $resource;
    }
    
    private function special($resource)
    {
        if (get_class($this->model) == "App\Cms\CmsUser" and \Auth::guard("cms")->user()->access_level == "Admin") {
            $resource->access_level = request()->get("access_level", "Standard");
            $resource->permissions = is_array(request()->get("permissions")) ? implode(",", array_filter(request()->get("permissions"))) : null;
        }
    }
    
    /**
     * Deal with saving the media data
     * 
     * @param  Illuminate\Database\Eloquent\Model $resource
     */
    protected function saveMedia($resource)
    {
        $class = get_class($resource);   
        // Empty first
        DB::table("cms_mediables")->where("mediable_id", $resource->id)->where("mediable_type", $class)->delete();
        // If we don't have a media field, just abort now
        if (! request()->has("cmsmedia")) return false;    
        
        //de(request()->get("cmsmedia"));
         
        foreach (request()->get("cmsmedia") as $mediable_category => $media) {
            // Now sync again
            if (empty($media)) continue;
            foreach ($media as $position => $cms_medium_id) {
                $resource->media()->attach($cms_medium_id, ["mediable_category" => $mediable_category, "position" => $position]);
            }
        }
        return true;
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