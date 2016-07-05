<?php

namespace Thinmartian\Cms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    
    /**
     * Return the image to the user (local disks only)
     * 
     * @param  integer  $cms_medium_id
     * @param  string   $filename
     * @param  integer  $width
     * @param  integer  $height
     * @param  string   $focal
     * @param  string   $extension
     */
    public function image($cms_medium_id, $filename, $width = null, $height = null, $focal, $extension)
    {
        $image = app()->make("Thinmartian\Cms\App\Services\Media\Image");
        $image->setCmsMedium($cms_medium_id);
        $path = $image->getImagePath($image->getImageFile($width, $height));
        
        
        dd(\Storage::disk("local")->get($path));
        
        dd($cms_medium_id,  $filename, $width, $height, $focal, $extension);
    }
    
}
