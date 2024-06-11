<div>
    @if (isset($user->experiment))
        @include('experiment-alert')
    @endif
    <div class="page-body">
        <div class="card">
            <div class="row">
                <div class="{{ isset($current_changing) ? 'col-md-3' : 'col-md-12' }} d-sm-block border-end">
                    <div class="card-header">
                        Настройка базальных скоростей
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-transparent">
                            @foreach ($user->basalChangings as $basal_changing)
                                <div class="row col-12">
                                    <div class="col-10">
                                        <button type="button" wire:click="changeBasal({{ $basal_changing->id }})"
                                            class="d-flex justify-content-between list-group-item list-group-item-action {{ $current_changing_id == $basal_changing->id ? 'active' : '' }}">
                                            {{ $basal_changing->showDatetime() }}&nbsp&nbsp&nbsp
                                            @if ($basal_changing->experiments->count() != 0)
                                                <span class="badge bg-orange text-orange-fg">Экспериментальная
                                                    запись</span>
                                            @endif
                                        </button>
                                    </div>
                                    @if ($basal_changing->experiments->count() == 0)
                                        <div class="col-2 d-flex align-items-center">
                                            <button
                                                wire:click="$dispatch('openModal', { component: 'data.delete-basal-modal', arguments: { id: {{ $basal_changing->id }} }})"
                                                class="btn btn-icon"><svg xmlns="http://www.w3.org/2000/svg"
                                                    width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-x">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M18 6l-12 12"></path>
                                                    <path d="M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        <h4 class="subheader mt-4">Создать новый набор базальных скоростей</h4>
                        <div class="list-group list-group-transparent">
                            <button type="button" wire:click="changeBasal"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_changing_id == 'create' ? 'active' : '' }}">
                                <div class="inline-flex">
                                    Создать&nbsp&nbsp&nbsp
                                    @if ($current_changing_id == -1)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-assembly">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path
                                                d="M19.875 6.27a2.225 2.225 0 0 1 1.125 1.948v7.284c0 .809 -.443 1.555 -1.158 1.948l-6.75 4.27a2.269 2.269 0 0 1 -2.184 0l-6.75 -4.27a2.225 2.225 0 0 1 -1.158 -1.948v-7.285c0 -.809 .443 -1.554 1.158 -1.947l6.75 -3.98a2.33 2.33 0 0 1 2.25 0l6.75 3.98h-.033z" />
                                            <path
                                                d="M15.5 9.422c.312 .18 .503 .515 .5 .876v3.277c0 .364 -.197 .7 -.515 .877l-3 1.922a1 1 0 0 1 -.97 0l-3 -1.922a1 1 0 0 1 -.515 -.876v-3.278c0 -.364 .197 -.7 .514 -.877l3 -1.79c.311 -.174 .69 -.174 1 0l3 1.79h-.014z" />
                                        </svg>
                                    @endif
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column">
                    @isset($current_changing)
                        <div class="card-body">
                            <div class="col-12">
                                <label class="form-label">
                                    Дата и время вступления в силу
                                </label>
                                <div class="col-12 row g-2">
                                    <div class="col-auto">
                                        <div class="input-group">
                                            <input wire:ignore.self type="datetime-local" x-init="call_flatpickr('changing_datetime');"
                                                class="form-control" id="changing_datetime"
                                                wire:model="formatted_current_datetime">
                                        </div>
                                    </div>
                                    <div class="col-auto">
                                        <button type="button" wire:click="loadBasal" class="btn">Автоматически
                                            заполнить</button>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-2">
                                <div class="card-body">
                                    <div class="row col-12 g-2">
                                        @foreach ($periods as $period)
                                            <div class="col-lg-2 col-sm-5">
                                                <h3 class="card-title text-wrap mt-4">
                                                    {{ $period->name }}</h3>
                                                <div>
                                                    <div class="row g-2">
                                                        <div class="col-auto">
                                                            <input type="text" class="form-control" @if ($current_changing != 'create' && $current_changing->experiments->count() != 0) disabled @endif
                                                                wire:model="formatted_basal_values.{{ $period->period - 1 }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                        @if ($current_changing == 'create' || $current_changing->experiments->count() == 0)
                            <button type="button" wire:click="saveBasal"
                                class="btn btn-primary btn-block">Сохранить</button>
                        @endif
                        </div>
                    @endisset
                </div>
            </div>
        </div>
    </div>
</div>