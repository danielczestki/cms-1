<?php

namespace Thinmartian\Cms\App\Services;
/*
|--------------------------------------------------------------------------
| Global service for the Thin Martian CMS
|--------------------------------------------------------------------------
|
| This service is a global service for the entire Thin Martian CMS sharing
| properties and methods to be used in many places across the CMS. 
|
*/

class Cms {
    
    /**
     * Protected YAML definition files (we do not alter or delete these in code)
     * 
     * @var array
     */
    protected $protectedYamls = [
        "Users"
    ];
    
    /**
     * Protected controllers (we do not alter or delete these in code)
     * Only within Core/. Custom/ is never protected
     * 
     * @var array
     */
    protected $protectedControllers = [
        "Controller",
        "Auth/AuthController",
        "Auth/PasswordController"
    ];
    
    /**
     * Protected models (we do not alter or delete these in code)
     * Only within Core/. Custom/ is never protected
     * 
     * @var array
     */
    protected $protectedModels = [
       "CmsUser",
       "Model",
       "Setter" 
    ];
    
}