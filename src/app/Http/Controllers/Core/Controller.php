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

class Controller extends BaseController
{
    
    /**
     * Include the resource traits
     */
    use ResourceHelpers, Listing, Form;
    
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
        if (method_exists($this, "beforeCreate")) {
            $this->beforeCreate($this->input);
        }
        $resource = $this->createResource($this->input);
        if (method_exists($this, "afterCreate")) {
            $this->afterCreate($resource, $this->input);
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
        dd("EDIT ID: $id METHOD");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd("DESTROY IDs: ". implode(",", request()->get("ids")) ." METHOD");
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