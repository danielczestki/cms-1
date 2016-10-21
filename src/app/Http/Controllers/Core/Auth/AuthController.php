<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core\Auth;

use Thinmartian\Cms\App\Models\Core\CmsUser;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class AuthController extends Controller {

    use AuthenticatesUsers {
        logout as performLogout;
    }

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


    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware("guest.cms", ["except" => "logout"]);
    }

    public function showLoginForm()
    {
        return view('cms::admin.auth.login');
    }

    public function logout(Request $request)
    {
        $this->performLogout($request);
        return redirect('admin/login');
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
            'firstname' => 'required|max:20',
            'surname' => 'required|max:20',
            'email' => 'required|email|max:255|unique:cms_users|confirmed',
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
