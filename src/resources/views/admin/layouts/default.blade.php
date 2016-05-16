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

    @yield("content")
    
    {{-- Output any custom/specific javascripts --}}
    @yield("js")
</body>
</html>