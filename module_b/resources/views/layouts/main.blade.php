<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield("title")</title>
    @yield("styles")
    <link rel="stylesheet" href="{{asset("css/bootstrap.min.css")}}">
    <link rel="stylesheet" href="{{asset("css/main.css")}}">
</head>
<body>
    <header class="navbar bg-primary">
        <div class="nav-item">
            <a href="/workspaces" class="nav-link text-white">Рабочие пространства</a>
        </div>
        <div class="nav-item">
            <a href="/bills" class="nav-link text-white">Счета</a>
        </div>
        <div class="nav-item">
            <a href="/tokens" class="nav-link text-white">АПИ Токены</a>
        </div>
    </header>
    <main class="mb-4">
        @if(session("message"))
            <div class="error-container position-absolute">
                <div class="alert alert-success" role="alert">
                    {{session("message")}}
                </div>
            </div>
        @elseif(session("error"))
            <div class="error-container position-absolute">
                <div class="alert alert-danger" role="alert">
                    {{session("error")}}
                </div>
            </div>
        @endif
        @yield("content")
    </main>
</body>
</html>
