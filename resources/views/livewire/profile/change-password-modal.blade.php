<x-modal>
    <x-slot name="tools">
    </x-slot>
    <x-slot name="title">
        <div class="row align-items-center justify-content-beetween">
            <div class="col">
                <h3 class="page-title">
                    Смена пароля
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
                                Старый пароль
                            </label>
                            <input wire:model="old_password" type="password" class="form-control  @error('old_password') is-invalid @enderror" name="old_password" autocomplete="current-old_password" placeholder="Введите пароль" autocomplete="off">
                            @error('old_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label">
                                Новый пароль
                            </label>
                        </div>
                        <div class="col-6">
                            <label class="form-label">
                                Повторите новый пароль
                            </label>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <div class="col-6">
                            <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" autocomplete="current-password" placeholder="Введите пароль"
                                autocomplete="off">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <input wire:model="password_confirmation" type="password" class="form-control  @error('password_confirmation') is-invalid @enderror" name="password_confirmation" autocomplete="current-password" placeholder="Повторите пароль" autocomplete="off">
                            @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
                <button class="btn btn-outline-primary" type="submit">Сохранить пароль</button>
            </div>
        </form>
    </x-slot>
    <x-slot name="buttons">
    </x-slot>
</x-modal>
