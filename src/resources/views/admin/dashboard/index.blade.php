@extends("cms::admin.layouts.default")

@section("title", "Dashboard")

@section("content")
    
    {{--var_dump(config("auth")) --}}
    
    <a href="{{ cmsaction("Auth\AuthController@showRegistrationForm") }}">Register</a>
    
    {{var_dump(\Auth::check())}}
    {{var_dump(\Auth::user())}}
    
@endsection