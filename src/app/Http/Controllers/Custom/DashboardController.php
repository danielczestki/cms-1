<?php

/**
 * TO BE WORKED ON, JUST HERE FOR NOW TO SHOW A PAGE.
 * GOD KNOWS WHAT WILL SHOW HERE :)
 */

namespace Thinmartiancms\Cms\App\Http\Controllers\Custom;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    
    
    public function index() {
        return view("cms::admin.dashboard.index");
    }
    

}