@extends("cms::admin.layouts.bare")

@section("title", "Register")

@section("content")
    
    You cannot register on the CMS. Please use the <a href="{{ route('admin.users.create') }}">CMS User area</a>.
    {{--
    {{ CmsForm::open(["method" => "POST", "url" => "Core\Auth\AuthController@register"]) }}
        
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
    --}}
    
@endsection