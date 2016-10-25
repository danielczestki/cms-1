<?php

namespace Thinmartian\Cms\App\Services\Media;

class UploadedFile
{
    
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
    public $visibility;
    
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
    public function __construct()
    {
        $this->disk = config("cms.cms.media_disk");
        $this->visibility = config("cms.cms.media_visibility");
        $this->basePath = config("cms.cms.media_path") . "/" . "media";
    }
}
