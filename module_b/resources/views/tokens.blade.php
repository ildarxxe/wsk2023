@extends("layouts.main")
@section("title", "Апи Токены")

@section("content")
    <h2 class="home-title text-center m-3">АПИ Токены</h2>
    <div class="token-create container">
        <a href="/tokens/create" class="btn btn-primary">Создать токен</a>
    </div>
    <div class="container border-dark text-center mt-2">
    @if($tokens)
        <div class="header-row text-white row bg-dark d-flex align-items-center justify-between">
            <p>ID</p>
            <p>ID Рабочего Пространства</p>
            <p>Имя</p>
            <p>Создан</p>
            <p>Отозван</p>
            <p>Действия</p>
        </div>
        @foreach($tokens as $token)
            <div class="token-row row bg-light d-flex align-items-center justify-between">
                <p>{{$token['id']}}</p>
                <p>{{$token['workspace_id']}}</p>
                <p>{{$token['name']}}</p>
                <p>{{$token['created_at']}}</p>
                <p class="{{!$token['revoked_at'] ? "text-green" : ""}}">{{$token['revoked_at'] ?? "Действует"}}</p>
                <div class="actions">
                    @if($token['revoked_at'])
                        <p>Нет</p>
                    @else
                        <form action="/tokens/{{$token['id']}}/revoke" method="POST" class="form-inline">
                            @csrf
                            <button type="submit">Отозвать</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    @else
        <p>Токены не найдены.</p>
    @endif
    </div>
@endsection
