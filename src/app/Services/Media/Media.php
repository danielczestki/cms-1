<?php

namespace Thinmartian\Cms\App\Services\Media;

trait Media {
     
    /**
     * Declare the valid/allow mediatypes
     * 
     * @var array
     */
    protected $mediaTypes = [
        "image" => [
            "label" => "Image",
            "icon" => "photo"
        ],
        "video" => [
            "label" => "Video",
            "icon" => "film"
        ],
        "document" => [
            "label" => "Document",
            "icon" => "file"
        ],
        "embed" => [
            "label" => "Embed",
            "icon" => "youtube"
        ],
    ];
     
     /**
      * Fetch the media disk from the config
      * 
      * @return string
      */
     public function getMediadisk()
     {
        return config("cms.cms.media_disk");
     }
    
    /**
     * Return the allowed media types
     * 
     * @return array
     */
    public function getMediaTypes($medianame = null)
    {
        foreach ($this->mediaTypes as $name => $type) {
            $this->mediaTypes[$name]["enabled"] = config("cms.cms.media_allow_". $name, true);
        }
        return $medianame ? array_get($this->mediaTypes, $medianame) : $this->mediaTypes;
    }
    
}