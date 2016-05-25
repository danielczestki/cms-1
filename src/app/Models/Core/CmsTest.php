<?php

namespace Thinmartian\Cms\App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class CmsTest extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ["title", "second", "third"];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    
}
