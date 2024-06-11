<div class="col d-flex flex-column">
    <div class="card-header">
        Социальные сети
    </div>
    <div class="card-body">
        <h3 class="card-title mt-2">Подключить аккаунт Telegram</h3>
        <div class="row col-12">
            @if ($user->tg_id == null)
            <div id="telegram" x-init="appendScriptToParent('telegram')">
            </div>
            @else
            <div class="alert alert-success" role="alert">
                Аккаунт Telegram подключен
            </div>
            <form wire:submit.prevent="disableTelegram" method="POST">
                @csrf
                @method('PUT')
                <button class="btn btn-outline-danger" type="submit">Отключить</button>
            </form>
            @endif
        </div>
    </div>
</div>
