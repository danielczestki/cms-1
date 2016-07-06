<?php

namespace Thinmartian\Cms\App\Models\Core;

use DB, Storage;
use Symfony\Component\Filesystem\Filesystem;

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
    protected $fillable = ["type", "cache_buster", "title", "uploaded", "filename", "extension", "original_name", "original_extension", "original_mime", "original_filesize"];
    
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
     * Get the document record associated with the medium.
     */
    public function document()
    {
        return $this->hasOne('Thinmartian\Cms\App\Models\Core\CmsMediumDocument');
    }
    
    
    
    /**
     * Boot methods
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();
        $filesystem = new Filesystem;

        self::saving(function($record) {
            $record->cache_buster = str_random(15);
        });
        
        self::deleting(function($record) use ($filesystem) {
            // remove the assets first, source first
            $sourcePath = config("filesystems.disks.local.root", storage_path("app")) . ("/cms/media/" . $record->id);
            $filesystem->remove($sourcePath);
            // Now on the disk
            Storage::disk(config("cms.cms.media_disk"))->deleteDirectory(config("cms.cms.media_path") . "/media/" . $record->id);
            // now kill the DB records
            DB::table("cms_mediables")->where("media_id", $record->id)->delete();
            if ($mapping = $record->image) $mapping->delete();
            if ($mapping = $record->document) $mapping->delete();
        });
    }
    
}
