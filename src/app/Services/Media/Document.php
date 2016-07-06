<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Models\Core\CmsMediumDocument;

class Document extends Media
{
    
    //
    // CRUD
    // 
    
    /**
     * Store the image
     * 
     * @return App\Cms\CmsMedium
     */
    public function store()
    {
        // create the parent media item first
        $this->storeCmsMedium();
        // lets upload the raw file
        $this->upload();
        // now persist the document
        if ($this->uploadedFile->uploaded) {
            $resource = new CmsMediumDocument;
            $this->cmsMedium->document()->save($resource);
        }
        // Return the model back to the controller
        return $this->cmsMedium;     
    }
    
    //
    // Validation
    // 
    
    /**
     * Get the form validation rules on create
     * 
     * @return array
    */
    public function validationOnCreate()
    {
        return [
            "type" => "required|in:". implode(",", array_keys($this->getMediaTypes())),
            "title" => "required|max:100",
            "file" => "required|file|mimes:". implode(",", $this->getMediaTypes("document.accepted"))
        ];
    }
    
    /**
     * Get the form validation rules on update
     * 
     * @return array
    */
    public function validationOnUpdate()
    {
        return [
            "title" => "required|max:100",
            "file" => "file|mimes:". implode(",", $this->getMediaTypes("document.accepted"))
        ];
    }
    
    //
    // Redirects
    // 

    /**
     * Redirect the user after store
     * 
     * @return  Illuminate\Routing\Redirector
    */
    public function redirectOnStore($cms_medium_id) 
    {
        return redirect()->route("admin.media.index")->withSuccess("Document successfully saved!");
    }
    
    /**
     * Redirect the user after update
     * 
     * @return  Illuminate\Routing\Redirector
    */
    public function redirectOnUpdate($cms_medium_id) 
    {
        return redirect()->route("admin.media.index")->withSuccess("Document successfully saved!");
    }
    
}