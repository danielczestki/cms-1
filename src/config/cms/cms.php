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
   
    "records_per_page_options" => range(10, 200, 10)
   
];