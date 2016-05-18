@extends("cms::admin.layouts.default")

@section("title", "Reset Password")

@section("content")
    
    @if (session('status'))
        <div>
            {{ session('status') }}
        </div>
    @endif
    
    {{ CmsForm::open(["method" => "POST", "url" => "Auth\PasswordController@sendResetLinkEmail"]) }}
        
        {{ CmsForm::email(["name" => "email", "label" => "Email"]) }}
        
        <div class="form-group">
            {{ CmsForm::submit(["label" => "Send Password Reset Link", "icon" => "envelope"]) }}
        </div>
        
    {{ CmsForm::close() }}
    
@endsection