CMS VERSION Click here to reset your password:
<a href="{{ $link = action('\Thinmartiancms\Cms\App\Http\Controllers\Auth\PasswordController@showResetForm', $token).'?email='.urlencode($user->getEmailForPasswordReset()) }}"> {{ $link }} </a>
