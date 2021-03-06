<?php

namespace App\Cms\System;

use Illuminate\Notifications\Notifiable;
use Thinmartian\Cms\App\Models\Core\Setter;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Thinmartian\Cms\App\Notifications\ResetPassword;

class CmsUser extends Authenticatable
{
    use Setter;
    use Notifiable;

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
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Return the permissions attribute
     *
     * @param  string $value
     * @return array
     */
    public function getPermissionsAttribute($value)
    {
        if (empty($value)) {
            return null;
        }
        return explode(",", $value);
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
