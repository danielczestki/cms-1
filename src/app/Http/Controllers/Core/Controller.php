<?php

namespace Thinmartiancms\Cms\App\Http\Controllers\Core;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Http\Requests;

use Thinmartiancms\Cms\App\Services\Resource\Listing;

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
        $this->getModel();
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listing = $this->grid();
        $columns = $this->listColumns;
        $perpage = $this->getRecordsPerPage();
        return view("cms::admin.resource.index", compact("listing", "columns", "perpage"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        dd("PARENT CREATE METHOD");
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
    
    
}
