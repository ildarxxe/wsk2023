@extends("layouts.main")
@section("title", "Создание токена")

@section("content")
    <h1 class="form-title m-3 text-center">Создайте токен</h1>
    <form class="container w-25" action="/tokens/create" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Имя токена:</label>
            <input class="form-control" type="text" name="name" id="name">
        </div>
        <div class="form-group">
            <label for="name">Рабочее пространство:</label>
            <select name="workspace_id" class="form-control">
                @foreach($workspaces as $ws)
                    <option value="{{$ws['id']}}">{{$ws['title']}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-button w-100">
            <button class="btn w-100 btn-primary" type="submit">Создать</button>
        </div>
    </form>
@endsection
