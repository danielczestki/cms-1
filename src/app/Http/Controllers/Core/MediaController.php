<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use CmsForm, CmsImage, CmsVideo, CmsDocument, CmsEmbed;
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
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->middleware("cms.media.valid", ["only" => ["create"]]);
        $this->middleware("cms.media.allowed", ["only" => ["create"]]);
    }
    
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
        $formtype = "create";
        $subtitle = "Upload new media";
        return view("cms::admin.media.form", compact("formtype", "subtitle"));
    }
    
}
