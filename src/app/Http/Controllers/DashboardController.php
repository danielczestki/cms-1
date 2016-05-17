<?php

namespace Thinmartiancms\Cms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;

class DashboardController extends Controller {
    
    
    public function index() {
        return view("cms::admin.dashboard.index");
    }
    

}