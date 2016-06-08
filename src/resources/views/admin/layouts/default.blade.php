<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield("title") - Thin Martian CMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    
    <link rel="icon" href="{{ asset('vendor/cms/favicon.png') }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Droid+Sans+Mono">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/cms/css/app.css') }}">
    {{-- Output any custom/specific stylesheets --}}
    @yield("css")
</head>
<body class="@yield('body_class')"><div id="app" class="Nav--closed" :class="{'Nav--closed': ! nav_open, 'Nav--open': nav_open}">

    <!-- Header -->
    <header class="Header">
        <a href="{{ route('cms-dashboard') }}" class="Header__logo Logo Logo--all-white Utility--image-replacement" title="Thin Martian CMS">Thin Martian CMS</a>
        <div class="Header__body">
            <div class="Header__tools Header__tools--left">
                <ul class="Header__options">
                    <li class="Header__option Header__option--nav"><a href="#" class="Header__link" v-on:click.prevent="primary_click"><i class="fa fa-caret-left"></i> <i class="fa fa-bars"></i> <i class="fa fa-caret-right"></i></a></li>
                    <li class="Header__option"><a href="{{ route('cms-dashboard') }}" class="Header__link{{ in_nav() ? ' Header__link--selected' : null }}" title="Back to dashboard"><i class="fa fa-home"></i></a></li>
                </ul>
            </div>
            <div class="Header__tools Header__tools--right">
                <ul class="Header__options">
                    <li class="Header__option Header__option--no-link Header__dropdowner">
                        <img src="{{ gravatar(Auth::user()->email) }}" alt="{{ Auth::user()->firstname . " " . Auth::user()->surname }}" class="Avatar Avatar--small">
                        <span class="Header__username">{{ Auth::user()->firstname }}</span>
                        <ul class="Header__dropdown">
                            <li class="Header__dropdown-option"><a href="{{ route('admin.users.edit', Auth::user()->id) }}" class="Header__link">Edit profile</a></li>
                            <li class="Header__dropdown-option"><a href="{{ cmsaction("Core\Auth\AuthController@logout") }}" class="Header__link">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </header>
    
    <!-- Primary nav -->
    @include("cms::admin.partials.layout.primary")
    
    <!-- Main content -->
    <main class="Main">
        @yield("content")  
    </main>
    
    <!-- Build version -->
    <div class="Build">Build {{ CMSVERSION }}</div>
    
    {{-- Output any custom/specific javascripts --}}
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="{{ asset('vendor/cms/js/app.js') }}"></script>
    @yield("js")
</div></body>
</html>