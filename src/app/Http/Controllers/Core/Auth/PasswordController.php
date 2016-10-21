<?php

namespace Thinmartian\Cms\App\Http\Controllers\Core\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class PasswordController extends Controller
{

    use ResetsPasswords;

    public function showResetForm(Request $request, $token = null)
    {
        return view('cms::admin.auth.passwords.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

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
     * Where do we send the user after successfully changing their password?
     *
     * @var string
     */
    protected $redirectTo = "/admin";

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
