<?php 

namespace Thinmartian\Cms\App\Services\Resource;
    
/*
|--------------------------------------------------------------------------
| Resource Redirects
|--------------------------------------------------------------------------
|
| Deals with all redirects for a resource, from creating to deleting and
| deal with the different button presses (Save, Save and Exit etc)
|
*/

trait Redirects
{
    
    /**
     * Parent redirect, determines what redirect and fires it back
     * 
     * @param  string                       $type     The type of request being made
     * @param  Illuminate\Database\Eloquent $resource The saved resource (if any)
     * @return Illuminate\Routing\Redirector
     */
    public function redirect($type = "store", $resource = null)
    {
        switch ($type) {
            case "store":
                return $this->redirectSave($resource, " created successfully");
            break;
            case "update":
                return $this->redirectSave($resource, " updated successfully");
            break;
            case "destroy":
                return $this->redirectDestroy();
            break;
        }
    }
    
    /**
     * Redirect after a store/create/edit/update
     * 
     * @param  Illuminate\Database\Eloquent $resource The saved resource
     * @param  string                       $success  Success mesage
     * @return Illuminate\Routing\Redirector
     */
    protected function redirectSave($resource, $success)
    {
        $flash = str_singular($this->name) . $success;
        if ($this->exiting()) {
            // save and exit
            return redirect(cmsaction($this->controller . "@index", true, $this->getFilters()))->with("success", $flash);
        } else {
            // save
            return redirect(cmsaction($this->controller . "@edit", true, array_merge(["id" => $resource->id], $this->getFilters())))->with("success", $flash);
        }
    }
    
    /**
     * Redirect after a delete
     * 
     * @return Illuminate\Routing\Redirector
     */
    protected function redirectDestroy()
    {
        return redirect(cmsaction($this->controller . "@index", true, $this->getFilters()))->with("success", str_plural(str_singular($this->name), count(request()->get("ids"))) . " deleted successfully");
    }
    
    /**
     * Does the user want to "exit" after saving?
     * 
     * @return boolean
     */
    private function exiting()
    {
        return array_key_exists("saveexit", request()->all());
    }
    
}