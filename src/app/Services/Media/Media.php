<?php

namespace Thinmartian\Cms\App\Services\Media;

use App\Cms\CmsMedium;
use Thinmartian\Cms\App\Services\Resource\ResourceInput;
use Storage;

trait Media {
    
    /**
     * Path to suffix on to the users config path
     * 
     * @var string
     */
    protected $path = "media";
    
    /**
     * Set the visibility of the uploaded files
     * 
     * @var string
     */
    protected $visibility = "public";
    
    /**
     * Declare the valid/allow mediatypes
     * 
     * @var array
     */
    protected $mediaTypes = [
        "image" => [
            "label" => "Image",
            "icon" => "photo",
            "accepted" => ["jpg", "jpe", "jpeg", "gif", "png"]
        ],
        "video" => [
            "label" => "Video",
            "icon" => "film",
            "accepted" => ["mp4", "mov", "wmv", "m4v", "avi"]
        ],
        "document" => [
            "label" => "Document",
            "icon" => "file",
            "accepted" => ["doc", "docx", "xls", "xlsx", "pps", "ppt", "pdf", "zip", "rtf", "txt"]
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
      * Fetch the media path from the config and add
      * the $this->path to it
      * 
      * @return string
      */
     public function getMediaPath()
     {
        return config("cms.cms.media_path") . "/" . $this->path;
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
    
    /**
     * Upload the file to the preferred disk first
     * 
     * @param  ResourceInput $input
     * @param  CmsMedium     $cms_media
     * @return array
     */
    protected function uploadFile(ResourceInput $input, CmsMedium $cms_media)
    {
        // basics
        $form = $input->getInput();
        $file = $form["file"];
        // set up the return array that contains all important info bout the file
        $result = $this->fileInfo($input, $cms_media);
        $uploaded = Storage::disk($this->getMediaDisk())->put("{$this->getMediaPath()}test.jpg", \File::get($file), $this->visibility);
        return $result;
    }
    
    /**
     * Build the return file info array
     * 
     * @param  ResourceInput $input
     * @param  CmsMedium     $cms_media
     * @return array
     */
    protected function fileInfo(ResourceInput $input, CmsMedium $cms_media)
    {
        $form = $input->getInput();
        $file = $form["file"];
        $result = [
            "disk" => $this->getMediaDisk(),
            "media_id" => $cms_media->id,
            "path" => $this->getMediaPath() . "/" . $cms_media->id,
            "filename" => str_random(15),
            "extension" => strtolower($file->guessExtension())
        ];
        dd($result);
    }
    
}