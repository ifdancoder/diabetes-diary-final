<div class="col d-flex flex-column">
    <div class="card-header">
        Связаться с администратором
    </div>
    <div class="card-body">
        <h3 class="card-title mt-2">Связь с администратором</h3>
        <div class="row col-12">
            <form wire:submit.prevent="connect" method="POST">
                @csrf
                @method('PUT')
                <button class="btn btn-outline-primary" type="submit">Сообщить администратору о готовности</button>
            </form>
        </div>
    </div>
</div>
