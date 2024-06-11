<div>
    @if (isset($user->experiment))
        @include('experiment-alert')
    @endif
    <div class="page-body">
        <div class="col-12">
            <a class="btn btn-primary mb-4" href="{{ route('home') }}">
                Создать новую запись
            </a>
        </div>
        <div class="card">
            <div class="row">
                <div class="col-md-1 d-sm-block border-end">
                    <div class="card-header">
                        Записи по датам
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-transparent">
                            @foreach ($records_dates as $record_date)
                                <button type="button" wire:click="changeTab('{{ $record_date }}')"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === $record_date ? 'active' : '' }}">{{ $record_date }}</button
                                    type="button">
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column mb-4">
                    <div class="card-header">
                        {{ $current_tab }}
                    </div>
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
                            @foreach ($records as $record)
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
                                                    Редактировать запись
                                                </a>
                                                <button
                                                    wire:click="$dispatch('openModal', { component: 'data.remove-record-modal', arguments: { id: {{ $record->id }}}})"
                                                    class="dropdown-item">
                                                    Удалить запись
                                                </button>
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
        </div>
    </div>
</div>
