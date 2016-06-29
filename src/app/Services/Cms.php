<?php

namespace Thinmartian\Cms\App\Services;
/*
|--------------------------------------------------------------------------
| Global service for the Thin Martian CMS
|--------------------------------------------------------------------------
|
| This service is a global service for the entire Thin Martian CMS sharing
| properties and methods to be used in many places across the CMS. 
|
*/

class Cms {
    
    /**
     * Protected files that we never delete/touch
     * 
     * @var array
     */
    protected $protectedFiles = [
        ".gitkeep",
        ".gitignore",
        "readme.md"
    ];
    
    /**
     * Protected controllers (we do not alter or delete these in code)
     * Only within Core/. Custom/ is never protected
     * 
     * @var array
     */
    protected $protectedControllers = [
        "Controller.php",
        "MediaController.php",
        "AuthController.php",
        "PasswordController.php"
    ];
    
    /**
     * Protected models (we do not alter or delete these in code)
     * Only within Core/. Custom/ is never protected
     * 
     * @var array
     */
    protected $protectedModels = [
        "CmsUser.php",
        "CmsMedium.php",
        "Model.php",
        "Setter.php" 
    ];
    
    /**
     * Protected migrations (we do not alter or delete these in code)
     * Only within the Package
     * 
     * @var array
     */
    protected $protectedMigrations = [
       "2016_05_16_000000_create_cms_users_table.php",
       "2016_05_16_100000_create_cms_password_resets_table.php",
       "2016_05_16_200000_create_cms_media_table.php",
       "2016_05_16_200000_create_cms_mediables_table.php"
    ];
    
    /**
     * Return the protected files
     * 
     * @return array
     */
    public function getProtectedFiles()
    {
        return $this->protectedFiles;
    }
    
    /**
     * Return the protected controllers
     * 
     * @param  boolean  $includeFiles  prefix with the protectedFiles array
     * @return array
     */
    public function getProtectedControllers($includeFiles = true)
    {
        return $includeFiles ? array_merge($this->getProtectedFiles(), $this->protectedControllers) : $this->protectedControllers;
    }
    
    /**
     * Return the protected models
     * 
     * @param  boolean  $includeFiles  prefix with the protectedFiles array
     * @return array
     */
    public function getProtectedModels($includeFiles = true)
    {
        return $includeFiles ? array_merge($this->getProtectedFiles(), $this->protectedModels) : $this->protectedModels;
    }
    
    /**
     * Return the protected migrations
     * 
     * @param  boolean  $includeFiles  prefix with the protectedFiles array
     * @return array
     */
    public function getProtectedMigrations($includeFiles = true)
    {
        return $includeFiles ? array_merge($this->getProtectedFiles(), $this->protectedMigrations) : $this->protectedMigrations;
    }
    
}