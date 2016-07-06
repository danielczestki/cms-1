<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use CmsForm;
use App\Cms\CmsMedium;
use App\Http\Controllers\Controller as BaseController;
use Thinmartian\Cms\App\Http\Requests\Core\MediaRequest;
use Illuminate\Http\Request;
use App\Http\Requests;

use Thinmartian\Cms\App\Services\Resource\ResourceInput;

class MediaController extends BaseController
{
    
    /**
     * The media type service we are using
     * 
     * @var Thinmartian\Cms\App\Services\Media\Image|Video|Document|Embed
     */
    protected $media;
    
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
        $this->setMedia(); // try to set the media object
    }
    
    /**
     * Display the listing of the media items.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = CmsMedium::orderBy("created_at", "desc")->get();
        return view("cms::admin.media.index", compact("listing"));
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
        $mediakey = request()->get("type");
        $mediatype = $this->media->getMediaTypes($mediakey);
        $formtype = "create";
        $subtitle = "<i class='fa fa-{$mediatype['icon']}'></i> Upload a new " . strtolower($mediatype["label"]);
        return view("cms::admin.media.form", compact("mediakey", "mediatype", "formtype", "subtitle"));
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
        return $this->delegate("redirectOnStore", $media);
    }
    
    /**
     * Show the form for editing a resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($cms_medium_id)
    {
        if (! $resource = CmsMedium::find($cms_medium_id)) return app()->abort(404);
        $mediakey = $resource->type;
        $this->setMedia($resource);
        $mediatype = $this->media->getMediaTypes($mediakey);
        $formtype = "edit";
        $subtitle = "<i class='fa fa-{$mediatype['icon']}'></i> Edit " . strtolower($mediatype["label"]);
        $preview = $this->media->preview();
        return view("cms::admin.media.form", compact("mediakey", "mediatype", "formtype", "subtitle", "resource", "preview"));
    }
    
    /**
     * Update the specified media in storage.
     *
     * @param  \Thinmartian\Cms\App\Http\Requests\Core\ResourceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MediaRequest $request, $id)
    {
        $media = CmsMedium::findOrFail($id);
        $this->setMedia($media);
        if (method_exists($this, "updating")) {
            $this->updating($media, $this->input);
        }
        if (method_exists($this, "saving")) {
            $this->saving($this->input);
        }
        // persist
        $media = $this->delegate("update");
        if (method_exists($this, "updated")) {
            $this->updated($media, $this->input);
        }
        if (method_exists($this, "saved")) {
            $this->saved($media, $this->input);
        }
        return $this->delegate("redirectOnUpdate", $media);
    }
    
    /**
     * Select the focal point of the image (images only use this)
     * 
     * @param  integer $cms_medium_id
     * @return \Illuminate\Http\Response
     */
    public function focal($cms_medium_id)
    {
        $resource = CmsMedium::find($cms_medium_id); // we know this exists, the middleware checks this
        $media = $this->setMedia($resource);
        return view("cms::admin.media.focal", compact("cms_medium_id", "resource", "media"));
    }
    
    /**
     * Set the focal point of the image (images only use this)
     * 
     * @param  integer $cms_medium_id
     * @return \Illuminate\Http\Response
     */
    public function focusing($cms_medium_id)
    {
        $resource = CmsMedium::find($cms_medium_id); // we know this exists, the middleware checks this
        $resource->image->focal = request()->get("focal", "center");
        $resource->image->save();
        return redirect()->route("admin.media.index")->withSuccess(ucfirst($resource->type) . " successfully saved!");
    }
    
    /**
     * Remove the specified media item from storage.
     *
     * @param  int  $id
     * @return array
     */
    public function destroy($cms_medium_id)
    {
        // Find it first
        if (! $resource = CmsMedium::find($cms_medium_id)) return $this->destroyResponse("Sorry, we couldn't find this media item");
        // We got it, proceed
        $resource->delete();
        // All done
        return $this->destroyResponse("OK", true);
    }
    
    /**
     * Return the array for the destroy method, that the AJAX can hook on to
     * 
     * @param  string  $message
     * @param  boolean $success
     * @return array
     */
    private function destroyResponse($message = null, $success = false)
    {
        return ["success" => $success, "message" => $message];
    }
    
    /**
     * Delegate a method to one of the services based on the type
     * 
     * @param  string $method The method to call on the service
     * @param  mixed  $params
     * @return App\Cms\CmsMedium
     */
    private function delegate($method, ...$params)
    {
        return $this->media->$method(...$params);
    }
    
    /**
     * Set the media prop, we can send the $type or default to what's
     * in the request()
     * 
     * @param App\Cms\CmsMedium $cms_medium
     */
    private function setMedia($cms_medium = null)
    {
        $service = new \Thinmartian\Cms\App\Services\Media\Media;
        $type = $cms_medium ? $cms_medium->type : request()->get("type");
        
        if ($service->isValidMediaType($type)) {
            $this->media = app()->make("Thinmartian\Cms\App\Services\Media\\" . ucfirst($type));
            $this->media->setInput($this->input);
            if ($cms_medium) $this->media->setCmsMedium($cms_medium);
            return $this->media;
        }
        return null;
    }
    
}
