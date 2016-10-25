<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Database\Eloquent\Model as BaseModel;

class CmsMediumVideo extends BaseModel
{
        
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cms_media_videos";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];
    
    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = "cms_medium_id";
    
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The relationships that should be touched on save.
     *
     * @var array
     */
    protected $touches = ['media'];
    
    /**
     * Media parent
     */
    public function media()
    {
        return $this->belongsTo("App\Cms\CmsMedium", "cms_medium_id");
    }
    
    /**
     * Ensure the job status is lowercase
     *
     * @param  string $value
     * @return string
     */
    public function getJobStatusAttribute($value)
    {
        return strtolower($value);
    }
    
    /**
     * Boot methods
     *
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::saving(function ($record) {
            $record->media->cache_buster = str_random(15);
            $record->media->save();
        });
    }
}
