<div>
    @if (isset($user->experiment))
        @include('experiment-alert')
    @endif
    <div class="page-body">
        <div class="container-xl">
            <div class="col-12 d-flex align-items-stretch">
                <div wire:poll.10s="actualizeChartData" class="card w-100">
                    <div class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            График уровней сахара</h3>
                    </div>
                    <div class="card-body">
                        <livewire:multi-line-chart :num="2" :names="['Уровень сахара по глюкометру', 'Уровень сахара по CGM']" :colors="['rgb(0, 255, 255)', 'rgb(0, 155, 118)']"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-xl">
        <div class="row col-12 g-2">
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Дата и время</h3>
                    </div>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="col">
                            <div>
                                <div class="input-group mt-1">
                                    <input type="datetime-local"
                                        class="@error('formatted_current_datetime') is-invalid @enderror form-control text-center"
                                        wire:model="formatted_current_datetime" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) @else wire:change="getRecord" @endif
                                        id="record_datetime" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @else x-init="call_flatpickr('record_datetime')" @endif
                                        value="{{ $formatted_current_datetime }}">
                                    @error('formatted_current_datetime')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) @else
                            <button type="button" wire:click="saveRecord"
                                class="btn btn-primary btn-block mt-3">Отправить</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3 ">
                            Указать уровень сахара</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="col">
                            <div>
                                <div class="form-label">Уровень сахара</div>
                                <div class="input-group mt-1">
                                    <input placeholder="5.2" type="number" min="0" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                        class="form-control text-center @error('sugar_level_value') is-invalid @enderror"
                                        wire:model="sugar_level_value">
                                    @error('sugar_level_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Указать количество углеводов</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="row col-12 g-2">
                            @foreach ($carbonhydrate_types as $carbonhydrate_type)
                                <div class="g-2 text-center d-flex align-items-center">
                                    <div class="col">
                                        <div>
                                            <div class="form-label">{{ $carbonhydrate_type->name }}</div>
                                            <div class="input-group mt-1">
                                                <input placeholder="{{ random_int(1, 56) }}" type="number" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                                    min="0" step="0.1"
                                                    class="form-control text-center @error($carbonhydrate_type->id == 1 ? 'fast_carbonhydrates_value' : ($carbonhydrate_type->id == 2 ? 'middle_carbonhydrates_value' : 'slow_carbonhydrates_value')) is-invalid @enderror"
                                                    wire:model="{{ $carbonhydrate_type->id == 1 ? 'fast_carbonhydrates_value' : ($carbonhydrate_type->id == 2 ? 'middle_carbonhydrates_value' : 'slow_carbonhydrates_value') }}">
                                                @error($carbonhydrate_type->id == 1 ? 'fast_carbonhydrates_value' :
                                                    ($carbonhydrate_type->id == 2 ? 'middle_carbonhydrates_value' :
                                                    'slow_carbonhydrates_value'))
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <div class="col">
                        <div class="row">
                            <button class="list-group-item list-group-item-action active align-items-center">
                                <h3 class="card-title text-center text-wrap mt-3">
                                    Обычный болюс</h3>
                            </button>
                        </div>
                        <div class="card-body g-2 text-center d-flex align-items-center">
                            <div class="col">
                                <div>
                                    <div class="form-label">Количество инсулина для обычного болюса
                                    </div>
                                    <div class="input-group mt-1">
                                        <input placeholder="2.5" type="number" min="0" step="0.1" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                            class="form-control @error('bolus_value') is-invalid @enderror text-center"
                                            wire:model="bolus_value">
                                        @error('bolus_value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <button class="list-group-item list-group-item-action active align-items-center">
                                <h3 class="card-title text-center text-wrap mt-3">
                                    Растянутый болюс</h3>
                            </button>
                        </div>
                        <div class="card-body g-2 text-center d-flex align-items-center">
                            <div class="col">
                                <div>
                                    <div class="form-label">Суммарное количество инсулина
                                    </div>
                                    <div class="input-group mt-1">
                                        <input placeholder="3.5" type="number" min="0" step="0.1" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                            class="form-control text-center @error('prolonged_bolus_value') is-invalid @enderror"
                                            wire:model="prolonged_bolus_value">
                                        @error('prolonged_bolus_value')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div>
                                    <div class="form-label">Интервал подачи инсулина
                                    </div>
                                    <div class="input-group mt-1">
                                        <input placeholder="01:30" type="time" id="prolonged_insulin_time" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @else x-init="call_flatpickr_time('prolonged_insulin_time')" @endif
                                            class="form-control text-center @error('prolonged_bolus_interval') is-invalid @enderror"
                                            wire:model="prolonged_bolus_interval">
                                        @error('prolonged_bolus_interval')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Указать уровень физической нагрузки</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="row col-12 g-2">
                            <div class="form-label">Уровень физической нагрузки
                            </div>
                            <select wire:model="physical_activity_session_value" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                class="form-select @error('physical_activity_session_value') is-invalid @enderror">
                                @foreach ($physical_activity_types as $physical_activity_type)
                                    <option value="{{ $physical_activity_type->id }}"
                                        {{ $physical_activity_type->id == $physical_activity_session_value ? 'selected' : '' }}>
                                        {{ $physical_activity_type->name }}</option>
                                @endforeach
                            </select>
                            @error('physical_activity_session_value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($active_physical_activity_session)
                                <div class="card w-100">
                                    <button class="list-group-item list-group-item-action active align-items-center">
                                        <h3 class="card-title text-center text-wrap mt-3">
                                            Действующий с
                                            {{ $active_physical_activity_session->record->showDatetime() }} уровень
                                            физической нагрузки</h3>
                                    </button>
                                    <div class="card-body">
                                        <div class="input-icon mb-3">
                                            <input class="form-control text-center"
                                                value="{{ $active_physical_activity_session->physicalActivityType->name }}"
                                                disabled>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Указать уровень стресса</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="row col-12 g-2">
                            <div class="form-label">Уровень стресса
                            </div>
                            <select wire:model="stress_level_session_value" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                class="form-select @error('stress_level_session_value') is-invalid @enderror">
                                @foreach ($stress_level_types as $stress_level_type)
                                    <option value="{{ $stress_level_type->id }}"
                                        {{ $stress_level_type->id == $stress_level_session_value ? 'selected' : '' }}>
                                        {{ $stress_level_type->name }}</option>
                                @endforeach
                            </select>
                            @error('stress_level_session_value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            @if ($active_stress_level_session)
                                <div class="card w-100">
                                    <button class="list-group-item list-group-item-action active align-items-center">
                                        <h3 class="card-title text-center text-wrap mt-3">
                                            Действующий с
                                            {{ $active_stress_level_session->record->showDatetime() }} тип стресса
                                        </h3>
                                    </button>
                                    <div class="card-body">
                                        <div class="input-icon mb-3">
                                            <input class="form-control text-center"
                                                value="{{ $active_stress_level_session->stressLevelType->name }}"
                                                disabled>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Указать временную базальную скорость (ВБС)</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="row col-12 g-2">
                            <div>
                                <div class="form-label">ВБС в %
                                </div>
                                <div class="input-icon mb-3">
                                    <input placeholder="60" type="number" min="0" step="10" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                        class="form-control text-center @error('temporal_basal_velocity_value') is-invalid @enderror"
                                        wire:model="temporal_basal_velocity_value">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                            class="icon icon-tabler icons-tabler-outline icon-tabler-percentage">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                            <path d="M17 17m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M7 7m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                            <path d="M6 18l12 -12" />
                                        </svg>
                                    </span>
                                    @error('temporal_basal_velocity_value')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <div class="form-label">Интервал ВБС
                                </div>
                                <div class="input-group mt-1">
                                    <input placeholder="01:30" type="time" id="vbs_time" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @else x-init="call_flatpickr_time('vbs_time')" @endif 
                                        wire:model="temporal_basal_velocity_interval"
                                        class="form-control text-center @error('temporal_basal_velocity_value') is-invalid @enderror">
                                    @error('temporal_basal_velocity_interval')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            @if ($active_temporal_basal_velocity_interval)
                                <div class="card w-100">
                                    <button class="list-group-item list-group-item-action active align-items-center">
                                        <h3 class="card-title text-center text-wrap mt-3">
                                            Действующая ВБС (Прекратит свое действие, если указать новую ВБС)</h3>
                                    </button>
                                    <div class="card-body">
                                        <div class="form-label">Действующая ВБС в %
                                        </div>
                                        <div class="input-icon mb-3">
                                            <input type="number" min="0" step="10"
                                                class="form-control text-center"
                                                wire:model="active_temporal_basal_velocity_value" disabled>
                                            <span class="input-icon-addon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-percentage">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M17 17m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M7 7m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                                                    <path d="M6 18l12 -12" />
                                                </svg>
                                            </span>
                                        </div>
                                        <div>
                                            <div class="form-label">Действующий интервал ВБС
                                            </div>
                                            <div class="input-group mt-1">
                                                <input type="time" id="active_vbs_time""
                                                    wire:model="active_temporal_basal_velocity_interval"
                                                    class="form-control text-center" disabled>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Сменить статус сна/бодрствования</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="row col-12 g-2">
                            <label class="form-check form-switch form-switch-lg">
                                <input wire:model="sleeping_session_value" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                    class="form-check-input @error('sleeping_session_value') is-invalid @enderror"
                                    type="checkbox">
                                <span class="form-check-label form-check-label-on">Вы спите</span>
                                <span class="form-check-label form-check-label-off">Вы бодрствуете</span>
                                @error('sleeping_session_value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Сменить статус недомогания/выздоровления</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="row col-12 g-2">
                            <label class="form-check form-switch form-switch-lg">
                                <input wire:model="desease_session_value" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                    class="form-check-input @error('desease_session_value') is-invalid @enderror"
                                    type="checkbox">
                                <span class="form-check-label form-check-label-on">Вы ощущаете недомогание</span>
                                <span class="form-check-label form-check-label-off">Вы здоровы</span>
                                @error('desease_session_value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3">
                            Отметить смену канюли</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <label class="form-check form-switch form-switch-lg">
                            <input wire:model="cannula_changing_status_value" @if (isset($record) && isset($record->record_type_id) && $record->record_type_id != 1) disabled @endif
                                class="form-check-input @error('cannula_changing_status_value') is-invalid @enderror"
                                type="checkbox">
                            <span class="form-check-label form-check-label-on">Вы сейчас сменили канюлю</span>
                            <span class="form-check-label form-check-label-off">Вы сейчас не меняли канюлю</span>
                            @error('cannula_changing_status_value')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-sm-12 d-flex align-items-stretch">
                <div class="card w-100">
                    <button class="list-group-item list-group-item-action active align-items-center">
                        <h3 class="card-title text-center text-wrap mt-3 ">
                            Настройка базальных скоростей</h3>
                    </button>
                    <div class="card-body g-2 text-center d-flex align-items-center">
                        <div class="col">
                            @if (isset($basal_values) && count($basal_values) > 0)
                                <a href="{{ route('basal', ['id' => $record->id]) }}"
                                    class="text-wrap btn btn-primary btn-block">Перейти к набору базальных
                                    скоростей</a>
                            @else
                                <a href="{{ route('basal') }}" class="text-wrap btn btn-primary btn-block">Перейти к
                                    созданию нового набора базальных скоростей</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
