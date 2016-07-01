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
    protected $fillable = ["type", "disk", "visibility", "cache_buster", "title", "uploaded", "filename", "extension", "original_name", "original_extension", "original_mime", "original_filesize"];
    
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ["created_at", "updated_at"];
    
    /**
     * Get the image record associated with the medium.
     */
    public function image()
    {
        return $this->hasOne('Thinmartian\Cms\App\Models\Core\CmsMediumImage');
    }
    
    /**
     * Boot methods
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::saving(function($record) {
            $record->cache_buster = str_random(15);
        });
    }
    
}
