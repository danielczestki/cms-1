<?php

namespace Thinmartian\Cms\App\Services\Media;

use Intervention\Image\ImageManager;


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