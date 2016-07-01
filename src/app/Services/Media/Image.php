<?php

namespace Thinmartian\Cms\App\Services\Media;

use Intervention\Image\ImageManager;
use Thinmartian\Cms\App\Services\Resource\ResourceInput;

use Thinmartian\Cms\App\Models\Core\CmsMediumImage;

class Image extends Media
{
    
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
        // lets upload the file
        $this->upload();
        // now persist the image
        if ($this->uploadedFile->uploaded) {
            $image = $this->intervention->make($this->input->file);
            $resource = new CmsMediumImage;
            $resource->aspect = $this->getAspect($image);
            $resource->original_width = $image->width();
            $resource->original_height = $image->height();
            $this->cmsMedium->image()->save($resource);
        }
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
     * Return the aspect ratio of the image
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

// namespace Thinmartian\Cms\App\Services\Media;

// use Intervention\Image\ImageManager;
// use Thinmartian\Cms\App\Services\Resource\ResourceInput;
// use App\Cms\CmsMedium;
// use Thinmartian\Cms\App\Models\Core\CmsMediumImage;

// /*
//     // LOGIC FOR SAVING THE CROPPED IMAGE TO THE USERS
//     // PREFERRED CENTER POINT
//     $width = 500;
//     $height = 500;
//     $x = 890 - ($width/2);
//     $y = 396 - ($height/2);
//     CmsImage::make(storage_path("image.jpg"))->crop($width, $height, $x, $y)->save(storage_path("image-updated.jpg"));
// */  


// class Image {
    
//     use Media;
    
//     /**
//      * @var Intervention\Image\ImageManager
//      */
//     protected $image;
    
//     /**
//      * constructor
//      */
//     public function __construct()
//     {
//         $this->image = new ImageManager(["driver" => config("cms.cms.intervention_driver", "gd")]);
//     }    
    
    
//     //
//     // CRUD
//     // 
    
//     /**
//      * Store the image
//      * 
//      * @param  ResourceInput $input
//      * @return App\Cms\CmsMedium
//      */
//     public function store(ResourceInput $input)
//     {
//         // create the parent media item first
//         $cms_media = $this->createMedia($input);
//         // upload the file
//         $upload = $this->upload($input, $cms_media);
//         // now store the image
//         $image = new CmsMediumImage;
//         $image->filename = "test";
//         $image->extension = "jpg";
//         $media->image()->save($image);
//         // return the parent media record
//         return $cms_media;
//     }
    
    
//     //
//     // FILE UPLOADS
//     // 
    
//     *
//      * Upload the file to the preferred disk
//      * 
//      * @param  ResourceInput $input
//      * @param  CmsMedium     $cms_media
//      * @return
     
//     public function upload(ResourceInput $input, CmsMedium $cms_media)
//     {
//         // Upload the file first
//         $file = $this->uploadFile($input, $cms_media);
//         dd($file);
//     }
    
    
//     //
//     // REDIRECTS
//     // 
    
//     /**
//      * Redirect the user after store
//      * 
//      * @return  Illuminate\Routing\Redirector
//      */
//     public function redirectOnStore(CmsMedium $cms_media) 
//     {
//         return redirect()->route("admin.media.focal", ["cms_medium_id" => $cms_media->id]);
//     }
    
    
//     //
//     // VALIDATION
//     // 
    
//     /**
//      * Get the form validation rules on create
//      * 
//      * @return array
//      */
//     public function validationOnCreate()
//     {
//         return [
//             "type" => "required|in:". implode(",", array_keys($this->getMediaTypes())),
//             "title" => "required|max:100",
//             "file" => "required|file|image|mimes:". implode(",", $this->getMediaTypes("image.accepted"))
//         ];
//     }
    
//     /**
//      * Get the form validation rules on update
//      * 
//      * @return array
//      */
//     public function validationOnUpdate()
//     {
//         return [];
//     }
    
    
//     //
//     // MAGIC
//     // 
    
//     /**
//      * Dynamically call the method on the ImageManger instance.
//      *
//      * @param  string $method
//      * @param  array  $parameters
//      * @return mixed
//      */
//     public function __call($method, $parameters)
//     {
//         return call_user_func_array([$this->image, $method], $parameters);
//     }    
    
// }