<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Thinmartian\Cms\App\Models\Core\Setter;

class CmsUser extends Authenticatable
{
    
    use Setter;
    
    /**
     * Set the YAML config filename
     * 
     * @var string
     */
    protected $yaml = "Users";
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cms_users";
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       "password", "remember_token",
    ];
    
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
     * Boot methods
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::saving(function($record) {
            // look for password in request, not $record as logout sends this and works differently
            // from our update/edit form and we end up hashing null when logging out :)
            if (request()->get("password")) {
                $record->password = bcrypt(request()->get("password"));
            } else {
                unset($record->password);
            }
        });
    }
    
}
