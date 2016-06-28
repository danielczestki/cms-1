<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use CmsForm, CmsImage, CmsVideo;
use Thinmartian\Cms\App\Http\Controllers\Core\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class MediaController extends Controller
{
    
    /**
     * Declare the name of this resource
     * 
     * @var string
     */
    protected $name = "Media";
    
    /**
     * Display the listing of the media items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("cms::admin.media.index");
    }
    
    /**
     * Before creating a new media, select the type
     * (image, video, document or embed)
     *
     * @return \Illuminate\Http\Response
     */
    public function type()
    {
        return view("cms::admin.media.type");
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $type = "create";
        view()->share("formtype", $type);
        $subtitle = "Upload new media";
        return view("cms::admin.media.form", compact("type", "subtitle"));
    }
    
}