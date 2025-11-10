@extends("layouts.main")
@section("title", "Рабочие пространства")

@section("content")
    <h2 class="home-title text-center m-3">Рабочие пространства</h2>
    <div class="token-create container">
        <a href="/workspaces/create" class="btn btn-primary">Создать рабочее пространство</a>
    </div>
    <div class="container border-dark text-center mt-2">
        @if($workspaces)
            <div class="header-row text-white row bg-dark d-flex align-items-center justify-between">
                <p>ID</p>
                <p>Название</p>
                <p>Описание</p>
                <p>Действия</p>
            </div>
            @foreach($workspaces as $workspace)
                <div class="token-row row bg-light d-flex align-items-center justify-between">
                    <p>{{$workspace['id']}}</p>
                    <p>{{$workspace['title']}}</p>
                    <p class="{{$workspace['description'] ? "" : "text-disable"}}">{{$workspace['description'] ?? "Отсутсвует"}}</p>
                    <div class="actions">
                        <a href="/workspaces/{{$workspace['id']}}/update" class="btn btn-primary">Изменить</a>
                        <a href="/workspaces/{{$workspace['id']}}" class="btn btn-primary">Открыть</a>
                    </div>
                </div>
            @endforeach
        @else
            <p>Токены не найдены.</p>
        @endif
    </div>
@endsection
