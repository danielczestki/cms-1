<?php

namespace Thinmartian\Cms\App\Services\Media;

use Intervention\Image\ImageManager;

class Image {
    
    use MediaHelpers;
    
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
    
    
    public function testing() {
        return "yada";
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