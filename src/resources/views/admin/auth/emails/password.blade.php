CMS VERSION Click here to reset your password:
<a href="{{ $link = action('\Thinmartian\Cms\App\Http\Controllers\Core\Auth\PasswordController@showResetForm', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
