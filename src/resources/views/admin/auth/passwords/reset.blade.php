@extends("cms::admin.layouts.default")

@section("title", "Change your Password")

@section("content")
    
    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif
    
    {{ CmsForm::open(["method" => "POST", "url" => "Auth\PasswordController@reset"]) }}
        
        {{ CmsForm::hidden(["name" => "token", "value" => $token]) }}
        
        {{ CmsForm::email(["name" => "email", "label" => "Email"]) }}
        {{ CmsForm::password(["name" => "password", "label" => "Password"]) }}
        {{ CmsForm::password(["name" => "password_confirmation", "label" => "Confirm Password"]) }}
        
        <div class="form-group">
            {{ CmsForm::submit(["label" => "Reset Password", "icon" => "refresh"]) }}
        </div>
        
    {{ CmsForm::close() }}
    
@endsection