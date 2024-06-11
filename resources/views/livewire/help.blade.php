<div>
    <div class="page-body">
        <div class="card">
            <div class="row">
                <div class="col-md-3 d-sm-block border-end">
                    <div class="card-header">
                        Справочная информация
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-transparent">
                            <button type="button" wire:click="changeTab('insulin-injection-types')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'insulin-injection-types' ? 'active' : '' }}">Типы
                                введения инсулина</button type="button">
                            <button type="button" wire:click="changeTab('physical-activity-types')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'physical-activity-types' ? 'active' : '' }}">Типы
                                физической активности</button type="button">
                            <button type="button" wire:click="changeTab('stress-level-types')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'stress-level-types' ? 'active' : '' }}">Типы
                                стресса</button сети type="button">
                            <button type="button" wire:click="changeTab('carbonhydrate-types')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'carbonhydrate-types' ? 'active' : '' }}">Типы
                                углеводов</button type="button">
                        </div>
                    </div>
                </div>
                <div class="col d-flex flex-column">
                    <div class="card-header">
                        @if ($current_tab === 'insulin-injection-types')
                            Типы введения инсулина
                        @elseif($current_tab === 'physical-activity-types')
                            Типы физической активности
                        @elseif ($current_tab === 'stress-level-types')
                            Типы стресса
                        @elseif ($current_tab === 'carbonhydrate-types')
                            Типы углеводов
                        @endif
                    </div>
                    @if ($current_tab === 'insulin-injection-types')
                        <div class="card-body">
                            <h3 class="card-title mt-3">Обычный болюс</h3>
                            <p>Обычный болюс представляет собой стандартную дозу инсулина, вводимую непосредственно
                                перед приёмом пищи для быстрого покрытия углеводов в пище и корректировки высокого
                                уровня сахара в крови. Этот тип болюса рассчитывается на основе текущего уровня сахара в
                                крови и количества углеводов, которые будут потреблены. Он быстро всасывается и
                                действует в течение нескольких часов.</p>
                            <h3 class="card-title mt-3">Растянутый болюс</h3>
                            <p>Растянутый болюс позволяет вводить инсулин постепенно в течение длительного времени. Этот
                                режим подходит для приёмов пищи с высоким содержанием жиров или белков, которые медленно
                                усваиваются и вызывают постепенное повышение уровня сахара в крови. Таким образом,
                                растянутый болюс помогает предотвратить резкие скачки сахара после еды и поддерживать
                                стабильный уровень глюкозы в течение более длительного времени.</p>
                        </div>
                    @elseif($current_tab === 'physical-activity-types')
                        <div class="card-body">
                            @foreach ($descriptions['physical_activity_types'] as $type)
                                <h3 class="card-title mt-3">{{ $type['name'] }}</h3>
                                <p>{{ $type['description'] }}</p>
                            @endforeach
                        </div>
                    @elseif ($current_tab === 'stress-level-types')
                        <div class="card-body">
                            @foreach ($descriptions['stress_level_types'] as $type)
                                <h3 class="card-title mt-3">{{ $type['name'] }}</h3>
                                <p>{{ $type['description'] }}</p>
                            @endforeach
                        </div>
                    @elseif ($current_tab === 'carbonhydrate-types')
                        <div class="card-body">
                            @foreach ($descriptions['carbonhydrate_types'] as $type)
                                <h3 class="card-title mt-3">{{ $type['name'] }}</h3>
                                <p>{{ $type['description'] }}</p>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
