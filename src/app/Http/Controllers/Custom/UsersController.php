<?php

namespace App\Cms\Http\Controllers;

use Thinmartian\Cms\App\Http\Controllers\Core\UsersController as CoreUsersController;
use Illuminate\Http\Request;
use App\Http\Requests;

use Thinmartian\Cms\App\Services\Resource\ResourceInput;

class UsersController extends CoreUsersController
{
    
    /**
     * This controller extends the core controller within the Thin Martian
     * CMS package. Your app should use this controller and not the core
     * controller, allowing you to customise this controller as you please.
     * 
     * The API is set out below as an example, none of the below methods are
     * required so you can remove if you wish. The beforeCreate method shows
     * an example of how to manipulate the form data during it's lifecycle.
     * 
     * Note: Be careful when overriding some properties/methods as they may be
     * used by the CMS and errors may occur!
     */
    
    protected function beforeCreate(ResourceInput $input)
    {
        // $input->add("field", "value");
        // $input->edit("field", "value");
        // $input->remove("field");
    }
    
}