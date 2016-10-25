<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Database\Eloquent\Model as BaseModel;

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
    
    /**
     * Get all of the media for the type... if applicable.
     */
    public function media($type = "media", $orderColumn = "position", $orderDir = "asc")
    {
        return $this->morphToMany("App\Cms\CmsMedium", "mediable", "cms_mediables", "mediable_id", "media_id")
            ->withPivot(["mediable_type", "mediable_category", "position"])
            ->wherePivot("mediable_category", $type)
            ->orderBy($orderColumn, $orderDir);
    }
}
