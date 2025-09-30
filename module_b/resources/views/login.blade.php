<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Авторизация</title>
    <link rel="stylesheet" href="{{asset("css/main.css")}}">
</head>
<body>
    <div class="form_wrapper">
        <h1>Атворизуйтесь</h1>
        <form method="POST" action="/login">
            @csrf
            <div class="form_label">
                <label for="name">Имя:</label>
                <input type="text" name="name" id="name">
            </div>
            <div class="form_label">
                <label for="password">Пароль:</label>
                <input type="password" name="password" id="password">
            </div>
            <button type="submit">Войти</button>
        </form>
    </div>
</body>
</html>
