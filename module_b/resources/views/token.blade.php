@extends("layouts.main")
@section("title", "Токены")
@section("content")
    <h1 class="workspace_title">Токены рабочего пространства</h1>
    <div class="management">
        <button class="create">Создать токен</button>
    </div>

    <form method="POST" class="hidden" action="/token/{{$workspace_id}}/create">
        @csrf
        <div class="form_label">
            <label for="name">Имя: <span class="required">*</span></label>
            <input required type="text" name="name" id="name">
        </div>
        <button type="submit">Создать</button>
    </form>
    @if(count($tokens) === 0)
        <div class="message">
            <p>У вас пока нет токенов. Нажмите "Создать токен", чтобы создать!</p>
        </div>
    @endif

    <div class="tokens">
        @foreach($tokens as $token)
            <div class="token_block {{$token["revoked_at"] !== null ? "revoked" : ""}}">
                <div class="token_block_inner">
                    <h2>Имя: {{$token['name']}}</h2>
                    <p>Создан: {{$token['created_at']}}</p>
                    @if($token["revoked_at"] !== null)
                        <p>Дата отзыва: {{$token['revoked_at']}}</p>
                    @else
                        <button class="revoke_token" id="{{$token['id']}}">Отозвать</button>
                    @endif
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

        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        const revoke_tokens = document.querySelectorAll(".revoke_token");

        revoke_tokens.forEach(revoke_token => {
            revoke_token.addEventListener("click", async () => {
                const tokenId = revoke_token.id;

                try {
                    const response = await fetch(`/token/revoke/${tokenId}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": csrfToken,
                            "Accept": "application/json"
                        }
                    });

                    if (response.ok) {
                        console.log(`Токен ID:${tokenId} успешно отозван.`);
                        window.location.reload();
                    } else {
                        const errorData = await response.json();
                        console.error("Ошибка при отзыве токена:", errorData.message || response.statusText);
                        alert("Ошибка: " + (errorData.message || "Неизвестная ошибка"));
                    }
                } catch (error) {
                    console.error("Сетевая ошибка:", error);
                    alert("Не удалось связаться с сервером.");
                }
            });
        });
    </script>
@endsection
