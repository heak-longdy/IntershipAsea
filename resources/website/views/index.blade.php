<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Website</title>
    {!! HTML::style('website/css/app.css') !!}
    {!! HTML::script('website/js/app.js') !!}

</head>

<body>
    @yield('index')
    @yield('script')
    {!! HTML::script('website/js/body.js') !!}
</body>

</html>
