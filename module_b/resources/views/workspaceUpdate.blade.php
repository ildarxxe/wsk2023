@extends("layouts.main")
@section("title", "Обновление рабочего пространства")

@section("content")
    <h1 class="form-title m-3 text-center">Обновите рабочую область</h1>
    <form class="container w-25" action="/workspaces/{{$workspace['id']}}/update" method="POST">
        @csrf
        @method("PUT")
        <div class="form-group">
            <label for="title">Название:</label>
            <input class="form-control" type="text" value="{{$workspace['title']}}" name="title" id="title">
        </div>
        <div class="form-group">
            <label for="description">Описание:</label>
            <input class="form-control" type="text" value="{{$workspace['description']}}" name="description" id="description">
        </div>
        <div class="form-button w-100">
            <button class="btn w-100 btn-primary" type="submit">Обновить</button>
        </div>
    </form>
@endsection
