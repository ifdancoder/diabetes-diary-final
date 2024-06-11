<x-modal>
    <x-slot name="tools">
    </x-slot>
    <x-slot name="title">
        <div class="row align-items-center justify-content-beetween">
            <div class="col">
                <h3 class="page-title">
                    Удаление набора базальных скоростей
                </h3>
            </div>
        </div>
    </x-slot>
    <x-slot name="content">
        <div class="container">
            <div class="col-11">
                <div class="mb-3 row">
                    <label class="form-label">
                        Вы действительно хотите удалить набор базальных скоростей?
                    </label>
                </div>
            </div>
        </div>
    </x-slot>
    <x-slot name="buttons">
        <button type="button" wire:click="deleteBasal" class="btn btn-danger btn-block">Удалить</button>
        <button type="button" wire:click="$dispatch('closeModal')" class="btn btn-primary btn-block">Отмена</button>
    </x-slot>
</x-modal>
