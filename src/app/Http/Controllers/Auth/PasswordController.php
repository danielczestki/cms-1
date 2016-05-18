<?php

namespace Thinmartiancms\Cms\App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

class PasswordController extends Controller
{

    use ResetsPasswords;
    
    /**
     * Ensure ther guard is cms, it should be default within admin/ anyway
     * 
     * @var string
     */
    protected $guard = "cms";
    
    /**
     * Ensure ther broker is cms, it should be default within admin/ anyway
     * 
     * @var string
     */
    protected $broker = "cms";
    
    /**
     * Change the views to be admin specific
     * 
     * @var string
     */
    protected $linkRequestView = "cms::admin.auth.passwords.email";
    protected $resetView = "cms::admin.auth.passwords.reset";

    /**
     * Where do we send the user after successfully changing their password?
     * 
     * @var string
     */
    protected $redirectPath = "/admin"; 
       
    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("guest.cms");
    }
}
