<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield("title") - Thin Martian CMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/cms/css/app.css') }}">
    {{-- Output any custom/specific stylesheets --}}
    @yield("css")
</head>
<body>

    <header style="box-sizing:border-box;margin: 0 0 24px;border-bottom: solid 2px #eee;width: 100%;float: left;padding: 12px">
        <div style="float: left">
            <a href="{{ route('cms-dashboard') }}">THIN MARTIAN CMS</a>
        </div>
        <div style="float: right">
            @if (\Auth::check())
                Welcome back, {{ Auth::user()->firstname }} <a href="{{ cmsaction("Auth\AuthController@logout") }}">Logout</a>
            @else
                <a href="{{ cmsaction("Auth\AuthController@showRegistrationForm") }}">Register</a>
                <a href="{{ cmsaction("Auth\AuthController@showLoginForm") }}">Login</a>
            @endif
        </div>
    </header>
    
    @yield("content")
    
    {{-- Output any custom/specific javascripts --}}
    @yield("js")
</body>
</html>