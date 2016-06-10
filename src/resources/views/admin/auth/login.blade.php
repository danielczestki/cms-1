@extends("cms::admin.layouts.session")

@section("body_class", "Session")
@section("title", "Login")

@section("content")
        
    {{ Form::open(["method" => "POST", "url" => cmsaction("Core\Auth\AuthController@login"), "class" => "Session-form Utility--valign-middle"]) }}<div>
        <i class="Logo Logo--grey Logo--background Logo--flex">Thin Martian CMS</i>
        <fieldset>
            <div class="Session-field">
                {{ Form::email("email", null, ["placeholder" => "Email address", "autofocus" => "autofocus", "class" => "Form__input"]) }}
                <small class="Session-error Utility--small">
                    @if ($errors->has("email"))
                        {{ $errors->first("email") }}
                    @endif
                </small>
            </div>
            <div class="Session-field">
                {{ Form::password("password", ["placeholder" => "Password", "class" => "Form__input"]) }}
                <small class="Session-error Utility--small">
                    @if ($errors->has("password"))
                        {{ $errors->first("password") }}
                    @endif
                </small>
            </div>
            <label for="remember" class="Form__checkbox">
                {{ Form::checkbox("remember", 1, false, ["id" => "remember"]) }}
                Remember me
            </label>
        </fieldset>
        {{ Form::button("Login", ["type" => "submit", "class" => "Button Button--medium Button--orange Button--stretch"]) }}
        <p class="Utility--small Utility--muted">Forgotten you password? No worries, let's <a href="{{ cmsaction('Core\Auth\PasswordController@reset') }}">reset it</a>!</p>
        <footer class="Session-footer Utility--small Utility--muted">
            &copy; {{ date("Y") }} <a href="http://www.thinmartian.com" target="_blank">Thin Martian</a> CMS. All Rights Reserved.
        </footer>
    </div>{{ Form::close() }}
    
@endsection