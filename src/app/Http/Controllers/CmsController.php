<?php

namespace Thinmartian\Cms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    
    /**
     * Return the media item to the user (local disks only)
     * 
     * @param  integer $cms_medium_id
     * @param  string $type
     * @param  string $filename
     * @param  integer $width
     * @param  integer $height
     * @param  string $extension
     */
    public function media($cms_medium_id, $type, $filename, $width = null, $height = null, $extension)
    {
        dd($cms_medium_id, $type, $filename, $width, $height, $extension);
    }
    
}
