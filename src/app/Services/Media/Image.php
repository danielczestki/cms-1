<?php

namespace Thinmartian\Cms\App\Services\Media;

use Intervention\Image\ImageManager;
use Thinmartian\Cms\App\Services\Resource\ResourceInput;


/*
    // LOGIC FOR SAVING THE CROPPED IMAGE TO THE USERS
    // PREFERRED CENTER POINT
    $width = 500;
    $height = 500;
    $x = 890 - ($width/2);
    $y = 396 - ($height/2);
    CmsImage::make(storage_path("image.jpg"))->crop($width, $height, $x, $y)->save(storage_path("image-updated.jpg"));
*/  


class Image {
    
    use Media;
    
    /**
     * @var Intervention\Image\ImageManager
     */
    protected $image;
    
    /**
     * constructor
     */
    public function __construct()
    {
        $this->image = new ImageManager(["driver" => config("cms.cms.intervention_driver", "gd")]);
    }    
    
    /**
     * Store the image
     * 
     * @param  ResourceInput $input
     * @return App\Cms\CmsMedium
     */
    public function store(ResourceInput $input)
    {
        $media = $this->createMedia($input);
        $form = $input->getInput();
        
        dd($media);
    }
    
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
    
    /**
     * Dynamically call the method on the ImageManger instance.
     *
     * @param  string $method
     * @param  array  $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->image, $method], $parameters);
    }    
    
}