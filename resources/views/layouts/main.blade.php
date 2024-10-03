<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

</head>
<body>


@yield('content')

</body>
</html>
