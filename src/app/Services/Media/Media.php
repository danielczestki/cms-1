<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Services\Resource\ResourceInput;
use App\Cms\CmsMedium;
use File, Storage;

class Media
{

    /**
     * @var string
     */
    protected $tempPath;

    /**
     * @var Thinmartian\Cms\App\Services\Resource\ResourceInput
     */
    protected $input;

    /**
     * When dealing with a cms_medium record, store it for easy access
     *
     * @var App\Cms\CmsMedium
     */
    public $cmsMedium;

    /**
     * All the details needed about the uploaded file
     *
     * @var Thinmartian\Cms\App\Services\Media\UploadedFile
     */
    protected $uploadedFile;

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
            "accepted" => ["mp4", "mov", "qt", "wmv", "asf", "m4v", "m4a", "avi", "3gp", "flv", "aac"]
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
     * Constructor
     */
    public function __construct()
    {
        $this->uploadedFile = new UploadedFile;
        $this->tempPath = storage_path("app/cms/temp");
        ini_set("default_socket_timeout", 9999);
        ini_set("max_execution_time", 9999);
        set_time_limit(0);
    }

    //
    // CRUD
    //

    /**
     * Create the parent cms_medium record
     *
     * @return void
     */
    protected function storeCmsMedium($uploaded = 0)
    {
        $record = new CmsMedium;
        $record->type = $this->input->type;
        $record->title = $this->input->title;
        $record->description = $this->input->description;
        $record->uploaded = $uploaded;
        $record->save();
        $this->cmsMedium = $record;
    }

    /**
     * Update the parent cms_medium record
     *
     * @return void
     */
    protected function updateCmsMedium()
    {
        $this->cmsMedium->title = $this->input->title;
        $this->cmsMedium->description = $this->input->description;
        $this->cmsMedium->uploaded = $this->uploadedFile->uploaded;
        $this->cmsMedium->filename = $this->uploadedFile->filename;
        $this->cmsMedium->extension = $this->uploadedFile->extension;
        $this->cmsMedium->original_name = $this->uploadedFile->originalName;
        $this->cmsMedium->original_extension = $this->uploadedFile->originalExtension;
        $this->cmsMedium->original_mime = $this->uploadedFile->originalMime;
        $this->cmsMedium->original_filesize = $this->uploadedFile->originalFilesize;
        $this->cmsMedium->save();
    }

    //
    // Uploads
    //

    /**
     * Upload the file to the preferred storage
     *
     * @return boolean
     */
    protected function upload()
    {
        // Start by setting some values in the UploadedFile object
        $file = $this->input->file;
        $this->uploadedFile->path = $this->uploadedFile->basePath . "/" . $this->cmsMedium->id;
        $this->uploadedFile->filename = $this->uploadedFile->filename ?: str_random(15);
        $this->uploadedFile->extension = strtolower($file->guessExtension());
        $this->uploadedFile->file = $this->uploadedFile->filename . "." . $this->uploadedFile->extension;
        $this->uploadedFile->originalName = $file->getClientOriginalName();
        $this->uploadedFile->originalExtension = $file->getClientOriginalExtension();
        $this->uploadedFile->originalMime = $file->getClientMimeType();
        $this->uploadedFile->originalFilesize = $file->getSize();
        // upload the file
        $this->uploadedFile->uploaded = Storage::disk($this->uploadedFile->disk)->put($this->getOriginalPath(true), File::get($file), $this->uploadedFile->visibility);
        // update the parent table
        $this->updateCmsMedium();
    }

    /**
     * Store a file on the disk
     *
     * @param  string $filepath Full path where the file should be stored
     * @param  string $file     The source file we want to move
     */
    protected function storeFile($destination, $source)
    {
        return Storage::disk($this->uploadedFile->disk)->put($destination, File::get($source), $this->uploadedFile->visibility);
    }

    //
    // Validation
    //

    /**
     * Determine if the media type is valid
     *
     * @param  string  $type
     * @return boolean
     */
    public function isValidMediaType($type = null)
    {
        if (! $type) return false;
        if (! array_key_exists($type, $this->getMediaTypes())) return false;
        if (! $this->getMediaTypes("{$type}.enabled")) return false;
        return true;
    }

    /**
     * Does a file exist on the storage system
     *
     * @param  string $filepath
     * @return boolean
     */
    public function fileExists($filepath)
    {
        return Storage::disk($this->uploadedFile->disk)->exists($filepath);
    }

    //
    // Getters
    //

    /**
     * Determine the location of the disk and return the URL
     *
     * @param  string $filepath
     * @return string
     */
    public function getPublicUrl($filepath)
    {
        if ($this->isLocal()) {
            return env("APP_URL") . "/storage/" . $filepath . "?" . $this->cmsMedium->cache_buster;
        } else {
            return config("cms.cms.media_cloud_url") . "/" . $filepath . "?" . $this->cmsMedium->cache_buster;
        }
    }

    /**
     * Return the original path
     *
     * @param  boolean $file Bind the file to the end of the path
     * @return string
     */
    public function getOriginalPath($file = false)
    {
        return $this->uploadedFile->path . "/original/" . ($file ? $this->uploadedFile->file : null);
    }

    /**
     * Return the path to a temp file
     *
     * @param  string $file Leave blank for the path only
     * @return string
     */
    public function getTempPath($file = null)
    {
        return $this->tempPath . "/" . $file;
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
            // kill video if they don't have their disk as s3
            if ($name == "video" and config("cms.cms.media_disk") != "s3") {
                $this->mediaTypes[$name]["enabled"] = false;
            }
        }
        return $medianame ? array_get($this->mediaTypes, $medianame) : $this->mediaTypes;
    }

    /**
     * Return the image quality
     *
     * @return integer
     */
    public function getImageQuality()
    {
        return config("cms.cms.media_image_quality", 100);
    }

    /**
     * Determine if the disk is local or cloud based
     *
     * @return boolean
     */
    public function isLocal()
    {
        return config("filesystems.disks.{$this->uploadedFile->disk}.driver") == "local";
    }

    /**
     * Get the icon by the media type
     *
     * @param  string $type
     * @return string
     */
    public function getIconByType($type)
    {
        return $this->mediaTypes[$type]["icon"];
    }

    //
    // Setters
    //

    /**
     * Set the input
     *
     * @param ResourceInput $input
     */
    public function setInput(ResourceInput $input)
    {
        $this->input = $input;
    }

    /**
     * Set cmsMedium prop
     *
     * @param mixed $cms_medium CmsMedium model OR cms_medium_id
     */
    public function setCmsMedium($cms_medium)
    {
        $this->cmsMedium = is_object($cms_medium) ? $cms_medium : CmsMedium::findOrFail($cms_medium);
        $this->setUploadedFile();
    }

    /**
     * Set the UploadedFile property. This should only be called
     * where the cmsMedium prop is set (above)
     */
    private function setUploadedFile()
    {
        $this->uploadedFile->path = $this->uploadedFile->basePath . "/" . $this->cmsMedium->id;
        $this->uploadedFile->filename = $this->cmsMedium->filename;
        $this->uploadedFile->extension = $this->cmsMedium->extension;
        $this->uploadedFile->file = $this->uploadedFile->filename . "." . $this->uploadedFile->extension;
        $this->uploadedFile->originalName = $this->cmsMedium->original_name;
        $this->uploadedFile->originalExtension = $this->cmsMedium->original_extension;
        $this->uploadedFile->originalMime = $this->cmsMedium->original_mime;
        $this->uploadedFile->originalFilesize = $this->cmsMedium->original_filesize;
        $this->uploadedFile->uploaded = $this->cmsMedium->uploaded;
    }

    /**
     * Set the ACL to public for a file
     */
    protected function setPublic($file)
    {
        Storage::setVisibility($file, "public");
    }

}
