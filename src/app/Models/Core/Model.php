<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Thinmartian\Cms\App\Models\Core\Setter;

class Model extends BaseModel
{
    
    use Setter;
    
    /**
     * Construct the CMS model
     *
     * @param  array  $attributes
     * @return void
     */   
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setCmsFillable();
        $this->setCmsDates();
    }
    
    
}
