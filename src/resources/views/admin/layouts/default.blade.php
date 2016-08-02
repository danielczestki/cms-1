<!DOCTYPE html>
<html lang="en">
<head>
    @include("cms::admin.layouts._meta")
</head>
<body class="@yield('body_class')"><div id="app" class="Nav--closed" :class="{'Nav--closed': ! nav_open, 'Nav--open': nav_open}">
    
    <!-- Media dialog popup -->
    <mediadialog :media_allowed="media_allowed" :open.sync="media_open" src="{{ route('admin.media.index') }}"></mediadialog>
    
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
                        <img src="{{ gravatar(Auth::guard('cms')->user()->email) }}" alt="{{ Auth::guard("cms")->user()->firstname . " " . Auth::guard("cms")->user()->surname }}" class="Avatar Avatar--small">
                        <span class="Header__username">{{ Auth::guard("cms")->user()->firstname }}</span>
                        <ul class="Header__dropdown">
                            <li class="Header__dropdown-option"><a href="{{ route('admin.users.edit', Auth::guard("cms")->user()->id) }}" class="Header__link">Edit profile</a></li>
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
    <div class="Build">Build {{-- CMSVERSION --}}</div>
    
    {{-- Output any custom/specific javascripts --}}
    <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
    <script src="{{ asset('vendor/cms/js/index.js') }}"></script>
    @yield("js")
</div></body>
</html>