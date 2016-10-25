<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Record per page
    |--------------------------------------------------------------------------
    |
    | Set the default records per page. This can be overriden in the filter
    | dropdown that will appear on most listing pages.
    |
    */

    "records_per_page" => 50,

    /*
    |--------------------------------------------------------------------------
    | Record per page options
    |--------------------------------------------------------------------------
    |
    | What minimum and maximum records per page should we have. This will
    | dictate the 'Results per page' dropdown
    |
    */

    "records_per_page_options" => range(10, 200, 10),

    /*
    |--------------------------------------------------------------------------
    | Default sorting
    |--------------------------------------------------------------------------
    |
    | Set the default sorting order and direction
    |
    */

    "default_sort_column" => "id",
    "default_sort_direction" => "desc",
    
    /*
    |--------------------------------------------------------------------------
    | Intervention image driver
    |--------------------------------------------------------------------------
    |
    | Set the default default driver used by the intervention image library.
    | We use 'gd' by default as this is more standard, but feel free to change
    | to 'imagick' if preferred.
    |
    */
   
    "intervention_driver" => "gd",
    
    /*
    |--------------------------------------------------------------------------
    | Media disk
    |--------------------------------------------------------------------------
    |
    | Thin Martian CMS utilises the wonderful Laravel Filesystem/Cloud storage.
    | Set the disk below you want Thin Martian CMS to upload your media to. This
    | of course must be a valid disk setup within your config/filesystems.php
    | configuration. Examples are 'public', 's3' etc.
    |
    | IMPORTANT: As per the Laravel documentation, if using a 'public' disk, be
    | sure to create a symbolic link between your storage and public, e.g.
    | public/storage which points to the storage/app/public directory.
    |
    */

    "media_disk" => "s3",
    
    /*
    |--------------------------------------------------------------------------
    | Media paths
    |--------------------------------------------------------------------------
    |
    | Set the path to store the media. This path will be suffixes to Laravels
    | default path for the disk (e.g 'local' would be storage/app/{your path}).
    | Thin Martian CMS also automatically add a few more folders after your path.
    | If changed after uploading media, all media will need to be moved to this
    | new location as the path is not persisted.
    |
    | The media_cloud_url should be your cloud endpoint for all http requests.
    | This is only required for cloud disks (above), local can be null
    |
    */
    
    "media_path" => "cms",
    "media_cloud_url" => "http://dev.thinmartian.cms.s3-eu-west-1.amazonaws.com",
    
    /*
    |--------------------------------------------------------------------------
    | Media visibility
    |--------------------------------------------------------------------------
    |
    | Set the visibility of uploaded files. This is only applicable to cloud
    | storage such as s3. By default this is set to "public" so it's visible to
    | all, anything else may prevent visitors viewing the media
    |
    */
    
    "media_visibility" => "public",
   
    /*
    |--------------------------------------------------------------------------
    | Media types
    |--------------------------------------------------------------------------
    |
    | Which of the four media types do you want to allow users to upload. All
    | uploads (apart from embed) will be stored on your media_disk that was
    | specified above.
    |
    */
    
    "media_allow_image" => true,
    "media_allow_video" => true,
    "media_allow_document" => true,
    "media_allow_embed" => true,
   
    /*
    |--------------------------------------------------------------------------
    | Media quality
    |--------------------------------------------------------------------------
    |
    | Set the quality of save media assets. This applies to jpeg images only.
    | Set the value between 1 and 100 (100 be highest quality).
    |
    */
    
    "media_image_quality" => 100,
    
    /*
    |--------------------------------------------------------------------------
    | Media Elastic Transcoder Pipeline ID
    |--------------------------------------------------------------------------
    |
    | This value is only required if you require video uploads in the media
    | library, if not leave this at null. Generate a new pipeline in your AWS
    | console. Login, select Elastic Transcoder > Creae New Pipeline, fill in
    | the form and the last page will show you the Pipeline ID
    | (e.g. 1469462371357-w92h52)
    |
    | Notes: Ensure your pipeline sets correct permissions for all jobs that
    | pass through it (e.g. ALL users need at least open/download permissions).
    |
    */
   
    "media_pipeline_id" => "1469462371357-w92h52",
    
    /*
    |--------------------------------------------------------------------------
    | Media Elastic Transcoder Preset ID
    |--------------------------------------------------------------------------
    |
    | This value is only required if you require video uploads in the media
    | library, if not leave this as is. By default we use Amazons Generic 1080p
    | preset which is fine in most cases. If you want to supply your own preset
    | then do so here, but in most cases this should be fine.
    |
    */
   
    "media_preset_id" => "1351620000001-000001",
   
];
