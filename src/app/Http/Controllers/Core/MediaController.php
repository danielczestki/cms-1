<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use CmsForm, CmsImage, CmsVideo, CmsDocument, CmsEmbed;
use App\Http\Controllers\Controller as BaseController;
use Thinmartian\Cms\App\Http\Requests\Core\MediaRequest;
use Illuminate\Http\Request;
use App\Http\Requests;

use Thinmartian\Cms\App\Services\Resource\ResourceInput;

class MediaController extends BaseController
{
    
    /**
     * @var Thinmartian\Cms\App\Services\Resource\ResourceInput
     */
    protected $input;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->input = new ResourceInput;
        $this->middleware("cms.media.valid", ["only" => ["create"]]);
        $this->middleware("cms.media.allowed", ["only" => ["create"]]);
        view()->share("controller", "MediaController");
        view()->share("filters", []);
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
        $mediatype = CmsImage::getMediaTypes(request()->get("type"));
        $formtype = "create";
        $subtitle = "<i class='fa fa-{$mediatype['icon']}'></i> Upload a new " . strtolower($mediatype["label"]);
        return view("cms::admin.media.form", compact("mediatype", "formtype", "subtitle"));
    }
    
    /**
     * Store the media to the DB
     *
     * @param  \Thinmartian\Cms\App\Http\Requests\Core\MediaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MediaRequest $request)
    {
        if (method_exists($this, "creating")) {
            $this->creating($this->input);
        }
        if (method_exists($this, "saving")) {
            $this->saving($this->input);
        }
        $media = $this->persist("store");
        if (method_exists($this, "created")) {
            $this->created($resource, $this->input);
        }
        if (method_exists($this, "saved")) {
            $this->saved($resource, $this->input);
        }
        dd("DONE");
    }
    
    /**
     * Determine how to persist the the media
     * 
     * @param  string $method The CRUD op we are doing (e.g. store, update etc)
     * @return App\Cms\CmsMedium
     */
    private function persist($method)
    {
        switch (request()->get("type")) {
            case "image" :
                return CmsImage::$method($this->input);
            break;
            case "video" :
                return CmsVideo::$method($this->input);
            break;
            case "document" :
                return CmsDocument::$method($this->input);
            break;
            case "embed" :
                return CmsEmbed::$method($this->input);
            break;
        }
    }
    
}
