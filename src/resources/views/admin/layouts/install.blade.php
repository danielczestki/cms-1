<!DOCTYPE html>
<html lang="en">
<head>
    <title>Thin Martian CMS</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, minimum-scale=1, maximum-scale=1">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Droid+Sans+Mono">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <style>
        html, body { height: 100% }
        body {
            margin: 0;
            padding: 0;
            background-color: #efefef;
            color: #000;
            font-family: 'Open Sans', Helvetica, Arial, 'sans-serif';
            font-size: 14px;
            font-weight: 400;
            line-height: 1.62
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        .Header {
            height: 60px;
            background: #ff5339;
            text-align: center;
            line-height: 60px;
        }
        .Header img {
            width: 30px;
            height: auto;
            vertical-align: middle;
            position: relative;
            top: -2px;
        }
        .Header__sub {
            height: 75px;
            margin: 0;
            background: #303b51;
            color: #fff;
            text-align: center;
            font-weight: 300;
            font-size: 35px;
            line-height: 75px;
        }
        .Main {
            height: calc(100% - 60px - 75px);
            padding: 12px;
            box-sizing: border-box;
        }
        h2 {
            margin: 0 0 24px;
            font-size: 26px;
            font-weight: 300;
        }
        .Box {
            max-width: 700px;
            margin: 0 auto;
            padding: 12px;
            background: #fff;
            border: solid 1px #cdcdcd;
        }
        code {
            padding: 2px 8px;
            font-family: 'Droid Sans Mono', monospace;
            background: #f7f7f7;
            color: #b81d7e;
            display: inline-block;
            font-size: 13px;
        }
        p {
            margin: 24px 0;
        }
        .Build {
            padding: 3px;
            font-family: 'Droid Sans Mono', monospace;
            font-size: 10px;
            background: rgba(255, 255, 255, .7);
            right: 0;
            bottom: 0;
            position: fixed
        }
        
        .Utility--valign-middle {
            text-align: center;
        }
        .Utility--valign-middle:before {
            content: '';
            height: 100%;
            vertical-align: middle;
            display: inline-block
        }
        .Utility--valign-middle > * {
             width: 100%;
            vertical-align: middle;
            display: inline-block;
        }
    </style>
</head>
<body>

    <header class="Header">
        <img src="data:image/png;base64,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" alt="">
    </header>
    <h1 class="Header__sub">Thin Martian CMS</h1>
    <main class="Main Utility--valign-middle"><div>
        @yield("content")
    </div></main>
    <div class="Build">Build {{ CMSVERSION }}</div>
    
</body>
</html>