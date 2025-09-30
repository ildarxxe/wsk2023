<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="{{asset("css/main.css")}}">
</head>
<body>
<header>
    <nav>
        <a href="/workspace">Рабочие пространства</a>
    </nav>
</header>
<main>
    @yield("content")
</main>
</body>
    @yield("script")
</html>
