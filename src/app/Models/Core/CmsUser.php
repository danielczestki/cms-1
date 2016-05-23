<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Foundation\Auth\User as Authenticatable;

class CmsUser extends Authenticatable
{
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
     * Merge the first and surname to make the full name
     * 
     * @return string
     */
    public function getFullnameAttribute()
    {
        return $this->firstname . " " . $this->surname;
    }
    
    /**
     * Boot methods
     * 
     * @return void
     */
    public static function boot()
    {
        parent::boot();

        self::creating(function($record) {
            $record->password = bcrypt($record->password);
        });

    }
    
}
