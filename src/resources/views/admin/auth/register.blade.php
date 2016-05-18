@extends("cms::admin.layouts.default")

@section("title", "Register")

@section("content")
    
    {{ CmsForm::open(["method" => "POST", "url" => "Auth\AuthController@register"]) }}
        
        {{ CmsForm::text(["name" => "firstname", "label" => "Firstame"]) }}
        {{ CmsForm::text(["name" => "surname", "label" => "Surname"]) }}
        
        {{ CmsForm::email(["name" => "email", "label" => "Email"]) }}
        {{ CmsForm::email(["name" => "email_confirmation", "label" => "Confirm Email"]) }}
        
        {{ CmsForm::password(["name" => "password", "label" => "Password"]) }}
        {{ CmsForm::password(["name" => "password_confirmation", "label" => "Confirm Password"]) }}
        
        <div class="form-group">
            {{ CmsForm::submit(["label" => "Regsiter", "icon" => "user"]) }}
        </div>
        
    {{ CmsForm::close() }}
    
@endsection