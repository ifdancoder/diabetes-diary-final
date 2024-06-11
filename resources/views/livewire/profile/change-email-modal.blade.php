<x-modal>
    <x-slot name="tools">
    </x-slot>
    <x-slot name="title">
        <div class="row align-items-center justify-content-beetween">
            <div class="col">
                <h3 class="page-title">
                    Смена электронной почты
                </h3>
            </div>
        </div>
    </x-slot>
    <x-slot name="content">
        <form wire:submit.prevent="changePassword">
            <div class="container">
                <div class="col-11">
                    <div class="mb-3 row">
                        <div class="col-12">
                            <label class="form-label">
                                Пароль
                            </label>
                            <input wire:model="password" type="password" class="form-control  @error('password') is-invalid @enderror" name="password" autocomplete="current-password" placeholder="Введите пароль" autocomplete="off">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-12">
                            <label class="form-label">
                                Новая электронная почта
                            </label>
                            <input wire:model="email" type="text" class="form-control  @error('email') is-invalid @enderror" name="email" autocomplete="current-password" placeholder="Новая электронная почта" autocomplete="off">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary" type="submit">Сохранить электронную почту'</button>
            </div>
        </form>
    </x-slot>
    <x-slot name="buttons">
    </x-slot>
</x-modal>
