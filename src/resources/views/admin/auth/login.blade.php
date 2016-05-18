@extends("cms::admin.layouts.default")

@section("title", "Login")

@section("content")
    
    
    {{ CmsForm::open(["method" => "POST", "url" => "Core\Auth\AuthController@login"]) }}

        {{ CmsForm::email(["name" => "email", "label" => "Email"]) }}
        {{ CmsForm::password(["name" => "password", "label" => "Password"]) }}
        {{ CmsForm::checkbox(["name" => "remember", "value" => "1", "label" => "Remember me?"]) }}

        <div class="form-group">
            {{ CmsForm::submit(["label" => "Login", "icon" => "sign-in"]) }}
            <a class="btn btn-link" href="{{ cmsaction('Core\Auth\PasswordController@reset') }}">Forgot Your Password?</a>
        </div>
        
    {{ CmsForm::close() }}
    
@endsection