<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Models\Core\CmsMediumDocument;

class Document extends Media
{
    
    //
    // Output
    //  
    
    /**
     * Fetch the URL to the document
     * 
     * @param  integer  $cms_medium_id  The cms_medium_id we are getting
     * @return string
     */
    public function get($cms_medium_id)
    {
        // Set and check
        $this->setCmsMedium($cms_medium_id);
        if ($this->cmsMedium->type != "document") return "#";
        // Send the public url back to them
        return $this->getPublicUrl($this->getOriginalPath(true));
    }
    
    /**
     * Return a preview of the item for listing across the place
     * 
     * @return string
     */
    public function preview()
    {
        return '<a href="'. $this->getPublicUrl($this->getOriginalPath(true)) .'" target="_blank">View current document</a> <small>('. $this->cmsMedium->original_name .')</small>';
    }
    
    /**
     * Return the fontawesome class for the listing
     * 
     * @param  App\Cms\CmsMedium $cms_medium
     * @return string
     */
    public function icon($cms_medium)
    {
        //"doc", "docx", "xls", "xlsx", "pps", "ppt", "pdf", "zip", "rtf", "txt"
        switch ($cms_medium->extension) {
            case "doc" :
            case "docx" :
            case "rtf" :
                return "file-word-o";
            break;
            case "xls" :
            case "xlsx" :
                return "file-excel-o";
            break;
            case "pps" :
            case "ppt" :
                return "file-powerpoint-o";
            break;
            case "pdf" :
                return "file-pdf-o";
            break;
            case "zip" :
                return "file-zip-o";
            break;
            case "txt" :
                return "file-txt-o";
            break;
            default :
                return "file";
            break;
        }
    }
    
    //
    // CRUD
    // 
    
    /**
     * Store the document
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
    
    /**
     * Update the document
     * 
     * @return App\Cms\CmsMedium
     */
    public function update()
    {
        // update the parent media item first
        $this->updateCmsMedium();
        if ($this->input->file) {
            // lets upload the raw file
            $this->upload();
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
    
    //
    // Getters
    //
    
    /**
     * Return the document path
     * 
     * @param  string   $filename   Omit for the path only
     * @return string
     */
    public function getDocumentPath($filename = null)
    {
        return $this->uploadedFile->path . "/original/" . $filename;
    }
    
}