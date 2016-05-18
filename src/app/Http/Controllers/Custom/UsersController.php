<?php

/**
 * WILL BE GENERATED FROM THE YAML CONFIG FILE!
 */

namespace Thinmartiancms\Cms\App\Http\Controllers\Custom;

use Thinmartiancms\Cms\App\Http\Controllers\Core\Controller;
use Illuminate\Http\Request;
use App\Http\Requests;

class UsersController extends Controller
{
    
    // ADD SEARCHABLE COLUMNS, AS WE CANT SQL SEARCH ACCESSORS, SO LET THE YAML DECIDE THIS
    
    /**
     * Declare the model for this resource
     * 
     * @var string
     */
    protected $modelName = "CmsUser";
    
    /**
     * Declare the columns that get returned on the listing
     * 
     * @var array
     */
    protected $listColumns = [
        "id" => "ID",
        "fullname" => "Full name",
        "email" => "Email",
        "created_at" => "Date added",
        "updated_at" => "Date updated"
    ];
    
}
