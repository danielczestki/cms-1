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
    | configuration. Examples are 'local', 's3' etc.
    |
    */

    "media_disk" => "s3",
    
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
    
    "media_allow_images" => true,
    "media_allow_videos" => true,
    "media_allow_documents" => true,
    "media_allow_embeds" => true,
   
];