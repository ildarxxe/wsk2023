@extends("layouts.main")
@section("title", "Рабочие пространства")
@section("content")
    <h1 class="workspace_title">Ваши рабочие пространства</h1>
    <div class="management">
        <button class="create">Создать новое</button>
    </div>

    <form method="POST" class="hidden" action="/workspace/create">
        @csrf
        <div class="form_label">
            <label for="title">Название: <span class="required">*</span></label>
            <input required type="text" name="title" id="title">
        </div>
        <div class="form_label">
            <label for="description">Описание:</label>
            <input type="text" name="description" id="description">
        </div>
        <button type="submit">Создать</button>
    </form>
    @if(count($workspaces) === 0)
        <div class="message">
            <p>У вас пока нет рабочих пространств. Нажмите "Создать новое", чтобы начать!</p>
        </div>
    @endif

    <div class="workspaces">
        @foreach($workspaces as $ws)
            <div class="workspace_block">
                <div class="workspace_block_inner">
                    <h2>Название: {{$ws['title']}}</h2>
                    @if($ws['description'] !== null)
                        <p>Описание: {{$ws['description']}}</p>
                    @endif
                    <nav>
                        <a href="/token/{{$ws['id']}}">Токены</a>
                        <a href="/bill/{{$ws['id']}}">Счета</a>
                        <a href="/quota/{{$ws['id']}}">Квоты</a>
                    </nav>
                </div>
            </div>
        @endforeach
    </div>
@endsection
@section("script")
    <script>
        const form = document.querySelector("form");
        const button = document.querySelector(".create");

        button.addEventListener("click", () => {
            form.classList.toggle("hidden");
        })
    </script>
@endsection
