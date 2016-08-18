<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Database\Eloquent\Model as Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class CmsApiKeys extends Model
{

    use SoftDeletes;
    
    /**
     * Set the YAML config filename
     * 
     * @var string
     */
    //protected $yaml = "Apikeys";
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cms_api_keys";

       
}
