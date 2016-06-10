<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield("title") - Thin Martian CMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Droid+Sans+Mono">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="{{ asset('vendor/cms/css/app.css') }}">
</head>
<body class="@yield('body_class')">
    
    <div class="Session-version Utility--muted">Build {{ CMSVERSION }}</div>
    <div class="Background"></div>
    
    @yield("content")
    
</body>
</html>