<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CmsUser extends Authenticatable
{
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "cms_users";
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'surname', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'password', 'remember_token',
    ];
    
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
            // from out update/edit form and we end up hashing null when logging out :)
            if (request()->get("password") and request()->get("password")) {
                $record->password = bcrypt(request()->get("password"));
            } else {
                unset($record->password);
            }
        });
    }
    
}
