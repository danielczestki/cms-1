<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use CmsYaml, CmsForm;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests;
use Thinmartian\Cms\App\Http\Requests\Core\ResourceRequest;
use Thinmartian\Cms\App\Services\Resource\ResourceInput;

use Thinmartian\Cms\App\Services\Resource\ResourceHelpers;
use Thinmartian\Cms\App\Services\Resource\Listing;
use Thinmartian\Cms\App\Services\Resource\Form;

abstract class Controller extends BaseController
{
    
    /**
     * Include the resource traits
     */
    use ResourceHelpers, Listing, Form;
    
    /**
     * @var string
     */
    protected $filters = ["records_per_page", "sort", "sort_dir", "search"];
    
    /**
     * Model class
     * 
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;
    
    /**
     * @var Thinmartian\Cms\App\Services\Resource\ResourceInput
     */
    protected $input;
    
    /**
     * @var string
     */
    protected $controller;
    
    /**
     * constructor
     */
    public function __construct(ResourceInput $input)
    {
        $this->controller = $this->name . "Controller";
        CmsYaml::setFile($this->name);
        $this->setModel();
        $this->input = $input;
        $this->sharedVars();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = $this->grid();
        $columns = $this->getListColumns();
        $perpage = $this->getRecordsPerPage();
        return view("cms::admin.resource.index", compact("title", "listing", "columns", "perpage"));
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
        $subtitle = CmsForm::subtitle($type, $this->name);
        $fields = $this->getFields();
        return view("cms::admin.resource.form", compact("type", "subtitle", "fields"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Thinmartian\Cms\App\Http\Requests\Core\ResourceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceRequest $request)
    {
        if (method_exists($this, "creating")) {
            $this->creating($this->input);
        }
        if (method_exists($this, "saving")) {
            $this->saving($this->input);
        }
        $resource = $this->createResource($this->input);
        if (method_exists($this, "created")) {
            $this->created($resource, $this->input);
        }
        if (method_exists($this, "saved")) {
            $this->saved($resource, $this->input);
        }
        return $this->redirect("store", $resource);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = "edit";
        view()->share("formtype", $type);
        $subtitle = CmsForm::subtitle($type, $this->name);
        $fields = $this->getFields();
        $resource = $this->getResource($id);
        return view("cms::admin.resource.form", compact("type", "subtitle", "fields", "resource"));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Thinmartian\Cms\App\Http\Requests\Core\ResourceRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceRequest $request, $id)
    {
        $resource = $this->getResource($id);
        if (method_exists($this, "updating")) {
            $this->updating($resource, $this->input);
        }
        if (method_exists($this, "saving")) {
            $this->saving($this->input);
        }
        $resource = $this->updateResource($resource, $this->input);
        if (method_exists($this, "updated")) {
            $this->updated($resource, $this->input);
        }
        if (method_exists($this, "saved")) {
            $this->saved($resource, $this->input);
        }
        return $this->redirect("update", $resource);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $subtitle = "Delete " . str_singular($this->name);
        // no ids? then go back
        if (! request()->get("ids")) return redirect()->back()->withError("Please select the ". strtolower(str_plural($this->name)) ." you want to delete");
        // this page is two step, if they havent confirmed, show them confirmation
        if (! request()->has("_confirmed")) return view("cms::admin.resource.destroy", compact("subtitle"));
        // they have confirmed, it's time to destroy!
        $this->deleteResources(request()->get("ids"));
        return $this->redirect("destroy");
        
    }
    
    
    //
    // Private
    // 
    
    
    /**
     * Set any shared variables that should exist across all views
     * 
     * @return void
     */
    private function sharedVars()
    {
        view()->share("title", $this->getMeta()["title"]);
        view()->share("_name", $this->name);
        view()->share("controller", $this->controller);
        view()->share("filters", $this->getFilters());
    }
    
    
}