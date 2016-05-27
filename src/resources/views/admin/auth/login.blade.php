@extends("cms::admin.layouts.bare")

@section("title", "Login")

@section("content")
        
    {{ Form::open(["method" => "POST", "url" => cmsaction("Core\Auth\AuthController@login")]) }}
    

        <p>Email: {{ Form::email("email") }}</p>
        <p>Password: {{ Form::password("password") }}</p>
        <p>{{ Form::checkbox("remember", 1) }} Remember me?</p>

        <div class="form-group">
            {{ Form::button("Login", ["type" => "submit"]) }}
            <a class="btn btn-link" href="{{ cmsaction('Core\Auth\PasswordController@reset') }}">Forgot Your Password?</a>
        </div>
        
    {{ Form::close() }}
    
@endsection