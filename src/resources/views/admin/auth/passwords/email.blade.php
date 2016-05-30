@extends("cms::admin.layouts.bare")

@section("body_class", "session")
@section("title", "Reset Password")

@section("content")
    
    {{ Form::open(["method" => "POST", "url" => cmsaction("Core\Auth\PasswordController@sendResetLinkEmail"), "class" => "Session-form Utility--valign-middle"]) }}<div>
        
        @if (session('status'))
            <p class="Session-status">{{ session('status') }}</p>
        @endif
        
        <i class="Logo Logo--grey Logo--background">Thin Martian CMS</i>
        <fieldset>
            <div class="Session-field">
                {{ Form::email("email", null, ["placeholder" => "Email address"]) }}
                <small class="Session-error Utility--small">
                    @if ($errors->has("email"))
                        {{ $errors->first("email") }}
                    @endif
                </small>
            </div>
        </fieldset>
        {{ Form::button("Send Password Reset Link", ["type" => "submit", "class" => "Button Button--medium Button--orange Button--stretch"]) }}
        <p class="Utility--small Utility--muted">Remembered your password? <a href="{{ cmsaction('Core\Auth\AuthController@login') }}">Login</a>.</p>
        <footer class="Session-footer Utility--small Utility--muted">
            &copy; {{ date("Y") }} <a href="http://www.thinmartian.com" target="_blank">Thin Martian</a> CMS. All Rights Reserved.
        </footer>
    </div>{{ Form::close() }}
    
@endsection