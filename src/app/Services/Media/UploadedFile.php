<?php

namespace Thinmartian\Cms\App\Services\Media;

class UploadedFile
{
    
    /**
     * Allowed focal points
     * 
     * @var array
     */
    public $allowedFocals = [
        "top-left",
        "top",
        "top-right",
        "left",
        "center",
        "right",
        "bottom-left",
        "bottom",
        "bottom-right"
    ];
    
    /**
     * Base path for all media uploads
     * 
     * @var string
     * @example cms/media
     */
    public $basePath;
    
    /**
     * Storage disk to use (fetched from the config)
     * 
     * @var string
     */
    public $disk;
    
    /**
     * Set the visibility of the uploaded files
     * 
     * @var string
    */
    public $visibility = "public";
    
    /**
     * @var string
     */
    public $path;
    
    /**
     * @var string
     */
    public $filename;
    
    /**
     * @var string
     */
    public $extension;
    
    /**
     * @var boolean
     */
    public $uploaded = false;
    
    /**
     * @var string
     */
    public $originalPath;
    
    /**
     * @var string
     */
    public $originalName;
    
    /**
     * @var string
     */
    public $originalExtension;
    
    /**
     * @var string
     */
    public $originalMime;
    
    /**
     * @var string
     */
    public $originalFilesize;
    
    /**
     * Constructor
     */
    public function __construct($cms_medium = null)
    {
        // TODO: For updates, pull in a $cms_medium and build the 
        // object from the data in there
        $this->disk = config("cms.cms.media_disk");
        $this->basePath = config("cms.cms.media_path") . "/" . "media";
    }
    
}