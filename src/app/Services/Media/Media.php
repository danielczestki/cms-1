<?php

namespace Thinmartian\Cms\App\Services\Media;

use Thinmartian\Cms\App\Services\Resource\ResourceInput;
use App\Cms\CmsMedium;
use File, Storage;

class Media
{
    
    /**
     * @var Thinmartian\Cms\App\Services\Resource\ResourceInput
     */
    protected $input;
    
    /**
     * When dealing with a cms_medium record, store it for easy access
     * 
     * @var App\Cms\CmsMedium
     */
    protected $cmsMedium;
    
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
     * Constructor
     */
    public function __construct()
    {
        $this->uploadedFile = new UploadedFile;
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
        $record->disk = $this->uploadedFile->disk;
        $record->visibility = $this->uploadedFile->visibility;
        $record->title = $this->input->title;
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
        $this->uploadedFile->filename = str_random(15);
        $this->uploadedFile->extension = strtolower($file->guessExtension());
        $this->uploadedFile->file = $this->uploadedFile->filename . "." . $this->uploadedFile->extension;
        $this->uploadedFile->originalName = $file->getClientOriginalName();
        $this->uploadedFile->originalExtension = $file->getClientOriginalExtension();
        $this->uploadedFile->originalMime = $file->getClientMimeType();
        $this->uploadedFile->originalFilesize = $file->getSize();
        // upload the file
        $this->uploadedFile->uploaded = Storage::disk($this->uploadedFile->disk)->put($this->getOriginalFile(), File::get($file), $this->uploadedFile->visibility);
        // update the parent table
        $this->updateCmsMedium();
    }
    
    //
    // Getters
    // 
    
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
     * Return the original path
     * 
     * @return string
     */
    public function getOriginalFile()
    {
        return "{$this->uploadedFile->path}/original/{$this->uploadedFile->file}";
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
    
}




// namespace Thinmartian\Cms\App\Services\Media;

// use App\Cms\CmsMedium;
// use Thinmartian\Cms\App\Services\Resource\ResourceInput;
// use Storage;

// trait Media {
    
//     /**
//      * Path to suffix on to the users config path
//      * 
//      * @var string
//      */
//     protected $path = "media";
    
//     /**
//      * Set the visibility of the uploaded files
//      * 
//      * @var string
//      */
//     protected $visibility = "public";
    
//     /**
//      * Declare the valid/allow mediatypes
//      * 
//      * @var array
//      */
//     protected $mediaTypes = [
//         "image" => [
//             "label" => "Image",
//             "icon" => "photo",
//             "accepted" => ["jpg", "jpe", "jpeg", "gif", "png"]
//         ],
//         "video" => [
//             "label" => "Video",
//             "icon" => "film",
//             "accepted" => ["mp4", "mov", "wmv", "m4v", "avi"]
//         ],
//         "document" => [
//             "label" => "Document",
//             "icon" => "file",
//             "accepted" => ["doc", "docx", "xls", "xlsx", "pps", "ppt", "pdf", "zip", "rtf", "txt"]
//         ],
//         "embed" => [
//             "label" => "Embed",
//             "icon" => "youtube"
//         ],
//     ];
     
//      /**
//       * Fetch the media disk from the config
//       * 
//       * @return string
//       */
//      public function getMediaDisk()
//      {
//         return config("cms.cms.media_disk");
//      }
     
//      /**
//       * Fetch the media path from the config and add
//       * the $this->path to it
//       * 
//       * @return string
//       */
//      public function getMediaPath()
//      {
//         return config("cms.cms.media_path") . "/" . $this->path;
//      }
    
//     /**
//      * Return the allowed media types
//      * 
//      * @return array
//      */
//     public function getMediaTypes($medianame = null)
//     {
//         foreach ($this->mediaTypes as $name => $type) {
//             $this->mediaTypes[$name]["enabled"] = config("cms.cms.media_allow_". $name, true);
//         }
//         return $medianame ? array_get($this->mediaTypes, $medianame) : $this->mediaTypes;
//     }
    
//     /**
//      * Create the parent cms_media record
//      * 
//      * @param  ResourceInput $input
//      * @return App\Cms\CmsMedium
//      */
//     protected function createMedia(ResourceInput $input)
//     {
//         $form = $input->getInput();
//         $record = new CmsMedium;
//         $record->title = $form["title"];
//         $record->disk = $this->getMediaDisk();
//         $record->save();
//         return $record;
//     }
    
//     /**
//      * Upload the file to the preferred disk first
//      * 
//      * @param  ResourceInput $input
//      * @param  CmsMedium     $cms_media
//      * @return array
//      */
//     protected function uploadFile(ResourceInput $input, CmsMedium $cms_media)
//     {
//         // basics
//         $form = $input->getInput();
//         $file = $form["file"];
//         // set up the return array that contains all important info bout the file
//         $result = $this->fileInfo($input, $cms_media);
//         $uploaded = Storage::disk($this->getMediaDisk())->put("{$this->getMediaPath()}test.jpg", \File::get($file), $this->visibility);
//         return $result;
//     }
    
//     /**
//      * Build the return file info array
//      * 
//      * @param  ResourceInput $input
//      * @param  CmsMedium     $cms_media
//      * @return array
//      */
//     protected function fileInfo(ResourceInput $input, CmsMedium $cms_media)
//     {
//         $form = $input->getInput();
//         $file = $form["file"];
//         $result = [
//             "disk" => $this->getMediaDisk(),
//             "media_id" => $cms_media->id,
//             "path" => $this->getMediaPath() . "/" . $cms_media->id,
//             "filename" => str_random(15),
//             "extension" => strtolower($file->guessExtension())
//         ];
//         dd($result);
//     }
    
//}