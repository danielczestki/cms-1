<!DOCTYPE html>
<html lang="en">
<head>
    @include("cms::admin.layouts._meta")
</head>
<body class="@yield('body_class')"><div id="media">
    
    <!-- Main content -->
    @yield("content")  
    
    {{-- Output any custom/specific javascripts --}}
    <script src="{{ asset('vendor/cms/js/index.js') }}"></script>
    @yield("js")
</div></body>
</html>