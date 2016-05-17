<?php

namespace Thinmartiancms\Cms\App\Http\Controllers\Auth;

use Thinmartiancms\Cms\App\CmsUser;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {
    
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;
    
    /**
     * Ensure ther guard is cms, it should be default within admin/ anyway
     * 
     * @var string
     */
    protected $guard = "cms";
    
    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/admin';
    protected $redirectAfterLogout = '/admin/login';
    
    /**
     * Define the custom views for the auth layer in the CMS
     * 
     * @var string
     */
    protected $registerView = "cms::admin.auth.register";
    protected $loginView = "cms::admin.auth.login";
    
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("guest.cms", ["except" => "logout"]);
    }
    
    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:255',
            'surname' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users|confirmed',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return CmsUser::create([
            'firstname' => $data['firstname'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

}