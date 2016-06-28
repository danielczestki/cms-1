<?php

namespace Thinmartian\Cms\App\Services\Media;

trait MediaHelpers {
     
     /**
      * Fetch the media disk from the config
      * 
      * @return string
      */
     public function getMediadisk()
     {
        return config("cms.cms.media_disk");
     }
    
}