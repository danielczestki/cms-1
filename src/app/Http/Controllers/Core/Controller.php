<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core;

use CmsYaml, CmsForm;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests;

use Thinmartian\Cms\App\Services\Resource\Listing;

class Controller extends BaseController
{
    
    /**
     * Include the resource traits
     */
    use Listing;
    
    /**
     * Model class
     * 
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;
    
    /**
     * constructor
     */
    public function __construct()
    {
        CmsYaml::setFile($this->name);
        $this->sharedVars();
        $this->setModel();
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
        return view("cms::admin.resource.form", compact("type", "subtitle"));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        return view()->share("title", $this->getMeta()->title);
    }
    
    
}
