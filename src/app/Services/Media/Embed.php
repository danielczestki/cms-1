<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Models\Core\CmsMediumEmbed;

class Embed extends Media
{
    
    //
    // Output
    // 
    
    /**
     * Fetch the embed
     * 
     * @param  integer  $cms_medium_id  The cms_medium_id we are getting
     * @param  integer  $width          The width we want the mbed code to be (null for default)
     * @param  integer  $height         The height we want the mbed code to be (null for default)
     * @return string
     */
    public function get($cms_medium_id = null, $width = null, $height = null)
    {
        // Set and check
        if ($cms_medium_id) $this->setCmsMedium($cms_medium_id);
        if ($this->cmsMedium->type != "embed") return;
        // Send the public url back to them
        $code = str_ireplace("'", "\"", $this->cmsMedium->embed->embed_code);
        if ($width) $code = preg_replace('/width="\d+"/i', sprintf('width="%d"', $width), $code);
        if ($height) $code = preg_replace('/height="\d+"/i', sprintf('height="%d"', $height), $code);
        return $code;
    }
    
    /**
     * Extract the url from the embed code
     * 
     * @param  App\Cms\CmsMedium $cms_medium
     * @return string
     */
    public function url($cms_medium)
    {
        $code = $cms_medium->embed->embed_code;
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $code, $match);
        if (! $match) return null;
        $url = $match[0];
        if ($url) return $url[0];
    }
    
    /**
     * Extract the domain from the embed code
     * 
     * @param  App\Cms\CmsMedium $cms_medium
     * @return string
     */
    public function domain($cms_medium)
    {
        $url = $this->url($cms_medium);
        if (! $parsed = parse_url($url)) return null;
        return @str_ireplace("www.", "", $parsed["host"]);
    }
     
    /**
     * Return a preview of the item for listing across the place
     * 
     * @return string
     */
    public function preview()
    {
        return $this->get(null, 480, 270);
    }
    
    //
    // CRUD
    // 
    
    /**
     * Store the embed
     * 
     * @return App\Cms\CmsMedium
     */
    public function store()
    {
        // create the parent media item first
        $this->storeCmsMedium();
        // now persist the embed
        $resource = new CmsMediumEmbed;
        $resource->embed_code = $this->input->embed_code;
        $this->cmsMedium->embed()->save($resource);
        // Return the model back to the controller
        return $this->cmsMedium;     
    }
    
    /**
     * Update the embed
     * 
     * @return App\Cms\CmsMedium
     */
    public function update()
    {
        // update the parent media item first
        $this->updateCmsMedium();
        $this->cmsMedium->embed->embed_code = $this->input->embed_code;
        $this->cmsMedium->embed->save();
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
            "embed_code" => "required|max:2000"
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
            "embed_code" => "required|max:2000"
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
        return redirect()->route("admin.media.index")->withSuccess("Embed successfully saved!");
    }
    
    /**
     * Redirect the user after update
     * 
     * @return  Illuminate\Routing\Redirector
    */
    public function redirectOnUpdate($cms_medium_id) 
    {
        return redirect()->route("admin.media.index")->withSuccess("Embed successfully saved!");
    }
    
}