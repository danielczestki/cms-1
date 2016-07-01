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
        // persist
        $media = $this->delegate("store", $this->input);
        if (method_exists($this, "created")) {
            $this->created($media, $this->input);
        }
        if (method_exists($this, "saved")) {
            $this->saved($media, $this->input);
        }
        // send them off
        return $this->delegate("redirectOnStore", $media);
    }
    
    /**
     * Set the focal point of the image (images only use this)
     * 
     * @param  integer $cms_medium_id
     * @return \Illuminate\Http\Response
     */
    public function focal($cms_medium_id)
    {
        dd("FOCAL: {$cms_medium_id}");
    }
    
    /**
     * Delegate a method to one of the services based on the type
     * 
     * @param  string $method The method to call on the service
     * @return App\Cms\CmsMedium
     */
    private function delegate($method, ...$params)
    {
        switch (request()->get("type")) {
            case "image" :
                return CmsImage::$method(...$params);
            break;
            case "video" :
                return CmsVideo::$method(...$params);
            break;
            case "document" :
                return CmsDocument::$method(...$params);
            break;
            case "embed" :
                return CmsEmbed::$method(...$params);
            break;
        }
    }
    
}
