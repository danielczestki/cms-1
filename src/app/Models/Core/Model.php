<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    
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
    }
    
    /**
     * Read the YAML and build the fillable fields
     */
    protected function setCmsFillable()
    {
        $this->fillable(cmsfillable($this->yaml));
    }
    
}
