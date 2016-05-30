@extends("cms::admin.layouts.bare")

@section("body_class", "session")
@section("title", "Change your Password")

@section("content")
    
    
    
    
    {{ Form::open(["method" => "POST", "url" => cmsaction("Core\Auth\PasswordController@reset"), "class" => "Session-form Utility--valign-middle"]) }}<div>
        {{ Form::hidden("token", $token) }}
        
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
            <div class="Session-field">
                {{ Form::password("password", ["placeholder" => "New password"]) }}
                <small class="Session-error Utility--small">
                    @if ($errors->has("password"))
                        {{ $errors->first("password") }}
                    @endif
                </small>
            </div>
            <div class="Session-field">
                {{ Form::password("password_confirmation", ["placeholder" => "Confirm new password"]) }}
                <small class="Session-error Utility--small">
                    @if ($errors->has("password_confirmation"))
                        {{ $errors->first("password_confirmation") }}
                    @endif
                </small>
            </div>
        </fieldset>
        {{ Form::button("Reset Password", ["type" => "submit", "class" => "Button Button--medium Button--orange Button--stretch"]) }}
        <p class="Utility--small Utility--muted">Remembered your password? <a href="{{ cmsaction('Core\Auth\AuthController@login') }}">Login</a>.</p>
        <footer class="Session-footer Utility--small Utility--muted">
            &copy; {{ date("Y") }} <a href="http://www.thinmartian.com" target="_blank">Thin Martian</a> CMS. All Rights Reserved.
        </footer>
    </div>{{ Form::close() }}
    
    
    
    
    
    
    
    
    
    
    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif
    
    {{ CmsForm::open(["method" => "POST", "url" => "Core\Auth\PasswordController@reset"]) }}
        
        {{ CmsForm::hidden(["name" => "token", "value" => $token]) }}
        
        {{ CmsForm::email(["name" => "email", "label" => "Email"]) }}
        {{ CmsForm::password(["name" => "password", "label" => "Password"]) }}
        {{ CmsForm::password(["name" => "password_confirmation", "label" => "Confirm Password"]) }}
        
        <div class="form-group">
            {{ CmsForm::submit(["label" => "Reset Password", "icon" => "refresh"]) }}
        </div>
        
    {{ CmsForm::close() }}
    
@endsection