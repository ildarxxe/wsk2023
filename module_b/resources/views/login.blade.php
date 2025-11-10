<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Вход</title>
    <link rel="stylesheet" href="{{asset("css/bootstrap.min.css")}}">
</head>
<body>
<main class="pt-lg-5 d-flex justify-center align-items-center flex-column">
    <h1 class="login-title text-center">Авторизуйтесь</h1>
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
    <div class="container w-25">
        <form action="/login" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" id="name" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>
            <div class="form-submit w-100">
                <button class="w-100 btn btn-primary" type="submit">Войти</button>
            </div>
        </form>
    </div>
</main>
</body>
</html>
