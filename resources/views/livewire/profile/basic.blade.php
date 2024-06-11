<div class="col d-flex flex-column">
    <div class="card-header">
        Основное
    </div>
    <div class="card-body">
        <h3 class="card-title">Имя и фамилия</h3>
        <div class="row g-3">
            <div class="col-auto">
                <div class="form-label">Имя</div>
                <input type="text" wire:input="updateNames" class="form-control" wire:model="first_name"
                    value="{{ $first_name }}">
            </div>
            <div class="col-auto">
                <div class="form-label">Фамилия</div>
                <input type="text" wire:input="updateNames" class="form-control" wire:model="last_name"
                    value="{{ $last_name }}">
            </div>
        </div>
        <h3 class="card-title mt-4">Email</h3>
        <!-- <p class="card-subtitle">This contact will be shown to others publicly, so choose it carefully.</p> -->
        <div>
            <div class="row g-2">
                <div class="col-auto">
                    <input type="text" class="form-control w-auto" value="{{ $user->email }}" disabled>
                </div>
                <div class="col-auto">
                    <button
                        onclick="Livewire.dispatch('openModal', { component: 'profile.change-email-modal', arguments: { id: {{ $user->id }} }})"
                        class="btn">
                        Сменить электронную почту
                    </button>
                </div>
            </div>
        </div>


        <h3 class="card-title mt-4">Пароль</h3>
        <div>
            <button
                onclick="Livewire.dispatch('openModal', { component: 'profile.change-password-modal', arguments: { id: {{ $user->id }} }})"
                class="btn">
                Установить новый пароль
            </button>
        </div>
    </div>
</div>
