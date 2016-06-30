<?php

namespace Thinmartian\Cms\App\Services\Media;

use App\Cms\CmsMedium;
use Thinmartian\Cms\App\Services\Resource\ResourceInput;

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
     public function getMediaDisk()
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
    
    /**
     * Create the parent cms_media record
     * 
     * @param  ResourceInput $input
     * @return App\Cms\CmsMedium
     */
    protected function createMedia(ResourceInput $input)
    {
        $form = $input->getInput();
        $record = new CmsMedium;
        $record->title = $form["title"];
        $record->disk = $this->getMediaDisk();
        $record->save();
        return $record;
    }
    
}