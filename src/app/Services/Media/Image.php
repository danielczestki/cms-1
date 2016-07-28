<?php

namespace Thinmartian\Cms\App\Services\Media;

use Intervention\Image\ImageManager;
use Thinmartian\Cms\App\Services\Resource\ResourceInput;

use Thinmartian\Cms\App\Models\Core\CmsMediumImage;

class Image extends Media
{
    
    /**
     * Width of the source image we create
     * 
     * @see  $this->uploadSource()
     * @var integer
     */
    protected $sourceWidth = 1400;
    
    /**
     * Path where we store the source image
     * 
     * @see  $this->uploadSource()
     * @var string
     */
    protected $sourcePath;
    
    /**
     * Set the generated image filename format
     * 
     * @var string
     */
    protected $imageFile = "{filename}-{width}x{height}-{focal}.{extension}";
    
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
     * @var Intervention\Image\ImageManager
    */
    protected $intervention;
    
    /**
     * constructor
    */
    public function __construct()
    {
        parent::__construct();
        $this->intervention = new ImageManager(["driver" => config("cms.cms.intervention_driver", "gd")]);
        $this->sourcePath = config("filesystems.disks.local.root", storage_path("app")) . "/cms/media"; // use their local disk if they have one
    }  
    
    //
    // Output
    //  
    
    /**
     * Fetch the URL to the image
     * 
     * @param  integer  $cms_medium_id  The cms_medium_id we are generating
     * @param  mixed    $width          Set to null for auto
     * @param  mixed    $height         Set to null for auto
     * @param  boolean  $force          Force a regenerationg of the image (ignore the file exist check)
     * @return string
     */
    public function get($cms_medium_id, $width = null, $height = null, $force = false)
    {
        // Set and check
        $this->setCmsMedium($cms_medium_id);
        if ($this->cmsMedium->type != "image") return false;
        // Generate the file name now so we can check for existence first
        $imagepath = $this->getImagePath($this->getImageFile($width, $height));
        // Already there?
        if (! $force and $this->fileExists($imagepath)) return $this->getPublicUrl($imagepath);
        // Generate the image and store
        $this->generate($width, $height);
        return $this->getPublicUrl($imagepath);
    }
    
    /**
     * Generate the image and store int
     * 
     * @param  mixed $width  Set to null for auto
     * @param  mixed $height Set to null for auto
     */
    private function generate($width = null, $height = null)
    {
        $image = $this->intervention->make($this->getSourcePath());
        $imagefile = $this->getImageFile($width, $height);
        $imagepath = $this->getImagePath($imagefile);
        $temppath = $this->getTempPath($imagefile);
        if ($width and $height) {
            // Do a fit and use the selected focal point
            $image->fit($width, $height, null, $this->cmsMedium->image->focal);
        } else {
            // Resize by width OR height only
            $image->resize($width, $height, function ($constraint) {
                $constraint->aspectRatio();
            });
        }
        // Save and push to storage
        $image->save($temppath, $this->getImageQuality());
        $this->storeFile($imagepath, $temppath);
        unlink($temppath);
    }
    
    /**
     * Return a preview of the item for listing across the place
     * 
     * @return string
     */
    public function preview()
    {
        return '<img src="'. $this->get($this->cmsMedium->id, 200, 200) .'" >';
    }
    
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
        // now persist the image
        if ($this->uploadedFile->uploaded) {
            $image = $this->intervention->make($this->input->file);
            $resource = new CmsMediumImage;
            $resource->aspect = $this->getAspect($image);
            $resource->original_width = $image->width();
            $resource->original_height = $image->height();
            $this->cmsMedium->image()->save($resource);
            // lets create the source file used for future variations
            $this->uploadSource();
        }
        // Return the model back to the controller
        return $this->cmsMedium;     
    }
    
    /**
     * Update the image
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
            // now persist the image
            if ($this->uploadedFile->uploaded) {
                $image = $this->intervention->make($this->input->file);
                $this->cmsMedium->image->aspect = $this->getAspect($image);
                $this->cmsMedium->image->original_width = $image->width();
                $this->cmsMedium->image->original_height = $image->height();
                $this->cmsMedium->image->save();
                // lets create the source file used for future variations
                $this->uploadSource();
            }
        }
        // Return the model back to the controller
        return $this->cmsMedium; 
    }
    
    /**
     * We create a small image locally that will be used as the base image
     * for all future resize variations. Otherwise, we may have to collected
     * from s3 on every new image which could get crazy
     * 
     * @return void
     */
    public function uploadSource()
    {
        // move the file first
        $this->input->file->move($this->getSourcePath(false), $this->uploadedFile->file);
        // now resize it 
        $image = $this->intervention->make($this->getSourcePath());
        $image->resize($this->sourceWidth, null, function ($constraint) {
            $constraint->aspectRatio();
        });
        $image->save(null, 100);
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
            "file" => "required|file|image|mimes:". implode(",", $this->getMediaTypes("image.accepted"))
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
            "file" => "file|image|mimes:". implode(",", $this->getMediaTypes("image.accepted"))
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
        return redirect()->route("admin.media.focal", ["cms_medium_id" => $cms_medium_id]);
    }
    
    /**
     * Redirect the user after update
     * 
     * @return  Illuminate\Routing\Redirector
    */
    public function redirectOnUpdate($cms_medium_id) 
    {
        return redirect()->route("admin.media.focal", ["cms_medium_id" => $cms_medium_id]);
    }
    
    //
    // Getters
    //
    
    /**
     * Return the image path
     * 
     * @param  string   $filename   Omit for the path only
     * @return string
     */
    public function getImagePath($filename = null)
    {
        return $this->uploadedFile->path . "/image/" . $filename;
    }
    
    /**
     * Build and return the generatred filename
     * 
     * @param  mixed $width
     * @param  mixed $height
     * @return string
     */
    public function getImageFile($width = null, $height = null)
    {
        return str_ireplace(["{filename}", "{width}", "{height}", "{focal}", "{extension}"], [$this->uploadedFile->filename, $width ?: 0, $height ?: 0, $this->cmsMedium->image->focal, $this->uploadedFile->extension], $this->imageFile);
    }
    
    /**
     * Get the source path (optionally with the file)
     * 
     * @param  boolean $file Bind the file to the end of the path
     * @return string
     */
    public function getSourcePath($file = true)
    {
        return $this->sourcePath . "/" . $this->cmsMedium->id . "/source/" . ($file ? $this->uploadedFile->file : null);
    }
    
    /**
     * Return the aspect ratio of the image
     * 
     * @param  Intervention\Image\Image $image
     * @return string
     */
    public function getAspect(\Intervention\Image\Image $image)
    {
        if ($image->width() > $image->height()) {
            return "landscape";
        } elseif ($image->width() < $image->height()) {
            return "portrait";
        } else {
            return "square";
        }
    }
    
    //
    // Magic
    // 

    /**
     * Dynamically call the method on the ImageManger instance.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
    */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->intervention, $method], $parameters);
    } 
    
}