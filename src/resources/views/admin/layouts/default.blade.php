<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield("title") - Thin Martian CMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    
    <link rel="icon" href="{{ asset('vendor/cms/favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/cms/css/app.css') }}">
    {{-- Output any custom/specific stylesheets --}}
    @yield("css")
</head>
<body class="@yield('body_class')">
    
    <!-- Header -->
    <header class="Header">
        <div class="Header__logo Logo Logo--all-white Utility--image-replacement" title="Thin Martian CMS">Thin Martian CMS</div>
        <div class="Header__body">
            <div class="Header__tools Header__tools--left">jhg</div>
            <div class="Header__tools Header__tools--right">jhg</div>
        </div>
    </header>
    
    <!-- Primary nav -->
    <nav class="Primary">
        <div class="Primary__body"><div class="Primary__overflow">
            TEXT
        </div></div>
    </nav>
    
    <?php /* ?>
    <header style="box-sizing:border-box;margin: 0 0 24px;border-bottom: solid 2px #eee;width: 100%;float: left;padding: 12px">
        <div style="float: left">
            <a href="{{ route('cms-dashboard') }}">THIN MARTIAN CMS</a>
            &nbsp; &nbsp; &nbsp; &nbsp;
            <a href="{{ route("admin.users.index") }}">Users</a>
        </div>
        <div style="float: right">
            @if (\Auth::check())
                Welcome back, {{ Auth::user()->firstname }} <a href="{{ cmsaction("Core\Auth\AuthController@logout") }}">Logout</a>
            @else
                <!-- <a href="{{ cmsaction("Core\Auth\AuthController@showRegistrationForm") }}">Register</a> -->
                <a href="{{ cmsaction("Core\Auth\AuthController@showLoginForm") }}">Login</a>
            @endif
        </div>
    </header>
    
    @yield("content")
    <?php */ ?>
    
    {{-- Output any custom/specific javascripts --}}
    <script src="{{ asset('vendor/cms/js/app.js') }}"></script>
    @yield("js")
</body>
</html>