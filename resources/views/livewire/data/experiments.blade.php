<div>
    @if (isset($user->experiment))
        @include('experiment-alert')
    @endif
    <div class="page-body">
        <div class="card">
            <div class="row">
                <div class="col-md-3 d-sm-block border-end">
                    <div class="card-header">
                        Сеансы прогнозирования
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-transparent">
                            @foreach ($user->experiments as $experiment)
                                <button type="button" wire:click="changeTab({{ $experiment->id }})"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab == $experiment->id ? 'active' : '' }}">Сеанс прогнозирования, созданный {{ $experiment->formattedCreatedAt() }}</button type="button">
                            @endforeach
                            @if (!isset($user->experiment))
                                <button type="button" wire:click="createExperiment"
                                    class="list-group-item list-group-item-action d-flex align-items-center">Создать</button
                                    type="button">
                            @endif
                        </div>
                    </div>
                </div>
                @if ($current_experiment)
                    <div class="col d-flex flex-column">
                        <div class="card-header">
                            <h3 class="card-title">Сеанс прогнозирования, созданный {{ $current_experiment->formattedCreatedAt() }}</h3>
                        </div>
                        @if (isset($user->experiment) && $user->experiment->id == $current_experiment->id)
                            <div class="card-body">
                                <div class="mb-3 row">
                                    <div class="col-4 g-3">
                                        <div class="row col-auto mb-3">
                                            <input type="datetime-local"
                                                class="@error('formatted_current_datetime') is-invalid @enderror form-control text-center"
                                                wire:model="formatted_current_datetime" id="record_datetime"
                                                x-init="call_flatpickr('record_datetime')" value="">
                                            @error('formatted_current_datetime')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="row col-auto g-2">
                                            <button wire:click="stopExperimentPredict"
                                                class="btn text-wrap btn-success">
                                                Окончить сеанс прогнозирования и получить, начиная с даты выше
                                            </button>
                                            <button wire:click="stopExperiment" class="btn text-wrap btn-info">
                                                Окончить сеанс прогнозирования
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (isset($current_experiment->datetime))
                            <div wire:poll.10s="actualizeChartData">
                                <livewire:multi-line-chart :num="2" :names="['Уровень сахара по CGM', 'Спрогнозированный уровень сахара']" :colors="['rgb(0, 155, 118)', 'rgb(255, 99, 132)']"
                                    :height="50" :width="90" />
                            </div>
                        @endif
                        <div class="card-header">
                            <h3 class="card-title">Записи, относящиеся к данному сеанс прогнозированияу</h3>
                        </div>
                        <div class="card-body">
                            <table class="table text-center card-table table-vcenter text-wrap datatable">
                                <thead>
                                    <tr>
                                        <th class="text-wrap">Дата и время</th>
                                        <th></th>
                                        <th class="text-wrap">Уровень сахара согласно глюкометру</th>
                                        <th class="text-wrap">Количество быстрых углеводов</th>
                                        <th class="text-wrap">Количество углеводов среднего времени действия</th>
                                        <th class="text-wrap">Количество медленных углеводов</th>
                                        <th class="text-wrap">Количество инсулина</th>
                                        <th class="text-wrap">Количество растянутого инсулина</th>
                                        <th class="text-wrap">Набор базальных скоростей</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($current_experiment->records as $record)
                                        <tr>
                                            <td>
                                                {{ $record->showDatetime() }}
                                            </td>
                                            <td>
                                                <span class="dropdown">
                                                    <button class="btn dropdown-toggle align-text-top"
                                                        data-bs-boundary="viewport" data-bs-toggle="dropdown"
                                                        aria-expanded="false">
                                                        Действия
                                                    </button>
                                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                                        <a class="dropdown-item"
                                                            href="{{ route('home', ['current_tab' => $record->id]) }}">
                                                            Просмотреть запись
                                                        </a>
                                                    </div>
                                                </span>
                                            </td>
                                            <td>
                                                {{ isset($record->sugarLevel) ? number_format($record->sugarLevel->val, 1) : '-' }}
                                            </td>
                                            <td>
                                                {{ $record->fastCarbonhydrate() != null ? number_format($record->fastCarbonhydrate()->val, 1) : '-' }}
                                            </td>
                                            <td>
                                                {{ $record->middleCarbonhydrate() != null ? number_format($record->middleCarbonhydrate()->val, 1) : '-' }}
                                            </td>
                                            <td>
                                                {{ $record->slowCarbonhydrate() != null ? number_format($record->slowCarbonhydrate()->val, 1) : '-' }}
                                            </td>
                                            <td>
                                                {{ $record->bolusInjection() != null ? number_format($record->bolusInjection()->val, 1) : '-' }}
                                            </td>
                                            <td>
                                                {{ $record->prolongedInjection() != null ? number_format($record->prolongedInjection()->val, 1) : '-' }}
                                            </td>
                                            <td>
                                                @if ($record->basalValues->count() > 0)
                                                    <a href="{{ route('basal', ['id' => $record->id]) }}"
                                                        class="text-decoration-none">
                                                        Перейти к набору
                                                    </a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
