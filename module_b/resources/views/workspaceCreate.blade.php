@extends("layouts.main")
@section("title", "Создание рабочего пространства")

@section("content")
    <h1 class="form-title m-3 text-center">Создайте рабочую область</h1>
    <form class="container w-25" action="/workspaces/create" method="POST">
        @csrf
        <div class="form-group">
            <label for="title">Название:</label>
            <input class="form-control" type="text" name="title" id="title">
        </div>
        <div class="form-group">
            <label for="description">Описание:</label>
            <input class="form-control" type="text" name="description" id="description">
        </div>
        <div class="form-button w-100">
            <button class="btn w-100 btn-primary" type="submit">Создать</button>
        </div>
    </form>
@endsection
