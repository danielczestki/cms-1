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
    protected $imageFile = "{filename}-{width}x{height}.{extension}";
    
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
    // Generate
    //  
    
    /**
     * The bread and butter, generates the images and moves it to the 
     * final disk and serves the path back to the browser
     * 
     * @param  integer  $cms_medium_id  The cms_medium_id we are generating
     * @param  mixed    $width          Set to null for auto
     * @param  mixed    $height         Set to null for auto
     * @return string
     */
    public function get($cms_medium_id, $width = null, $height = null)
    {
        // Set and check
        $this->setCmsMedium($cms_medium_id);
        if ($this->cmsMedium->type != "image") return false;
        // Generate the file name now so we can check for existence first
        $imagepath = $this->getImagePath($this->getImageFile($width, $height));
        // Already there?
        if ($this->fileExists($imagepath)) return $this->getPublicUrl($imagepath);
        // generate the image
        // move to final resting place
        // return full url string
        
        dd($this);
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
        return [];
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
        return str_ireplace(["{filename}", "{width}", "{height}", "{extension}"], [$this->uploadedFile->filename, $width ?: 0, $height ?: 0, $this->uploadedFile->extension], $this->imageFile);
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