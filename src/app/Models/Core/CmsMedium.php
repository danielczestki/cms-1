<?php

namespace Thinmartian\Cms\App\Models\Core;

class CmsMedium extends Model
{
    
    /**
     * Set the YAML config filename
     * 
     * @var string
     */
    protected $yaml = "Media";
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cms_media";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["title", "type", "disk", "status", "filename", "extension", "title", "original_name", "original_extension", "original_filesize"];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ["created_at", "updated_at"];
}
