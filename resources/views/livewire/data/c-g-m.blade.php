<div>
    <div class="page-body">
        <div class="card">
            <div class="row">
                <div class="col-md-3 d-sm-block border-end">
                    <div class="card-header">
                        Загрузка данных из CGM
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-transparent">
                            @foreach ($user->getMedia('cgm') as $cgm)
                                <button type="button" wire:click="changeTab({{ $cgm->id }})"
                                    class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab == $cgm->id ? 'active' : '' }}">Медицинские
                                    данные от
                                    {{ \Carbon\Carbon::parse($cgm->created_at)->setTimezone($user->personalSettings->timezone->timezone_name)->format('d.m.Y H:i') }}</button
                                    type="button">
                            @endforeach
                            <button type="button" wire:click="changeTab('create')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab == 'create' ? 'active' : '' }}">Загрузить
                                файл с медицинскими данными</button type="button">
                        </div>
                    </div>
                </div>
                @if (isset($current_tab))
                    <div class="col d-flex flex-column">
                        <div class="card-header">
                            @if ($current_tab === 'create')
                                <h3 class="card-title">Загрузка данных из CGM</h3>
                            @else
                                <h3 class="card-title">Медицинские данные от
                                    {{ \Carbon\Carbon::parse($newAttachment->created_at)->setTimezone($user->personalSettings->timezone->timezone_name)->format('d.m.Y H:i') }}
                                </h3>
                            @endif
                        </div>
                        <div class="card-body">
                            @if ($current_tab === 'create')
                                <div x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
                                    x-on:livewire-upload-finish="uploading = false; progress = 0; $wire.addAttachment();"
                                    x-on:livewire-upload-progress="progress = $event.detail.progress"
                                    class="text-center">

                                    <div class="mb-3">
                                        <div class="form-label">Выберите файл с медицинскими данными</div>
                                        <input type="file" class="form-control" wire:model="newAttachment">
                                    </div>
                                    <div x-show="uploading" class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div x-text="progress + '%'"
                                                class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="progress progress-sm mr-2">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    :style="{ width: progress + '%' }" aria-valuenow="progress"
                                                    aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <livewire:multi-line-chart :num="1" :names="['Уровень сахара по CGM']" :colors="['rgb(0, 155, 118)']"
                                    :height="50" :width="90" />
                                @if (!$is_changing_bounds)
                                    <button wire:click="changeBounds" class="btn text-wrap btn-success">
                                        Выбрать границы для добавления исторических данных в дневник
                                    </button>
                                @else
                                    <div class="alert alert-important alert-info" role="alert">
                                        <div class="d-flex">
                                            <div>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon"
                                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                                    <path d="M12 9h.01"></path>
                                                    <path d="M11 12h1v4h1"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                @if (!$flip_flop)
                                                    Выберите левую границу для добавления исторических данных в дневник
                                                @else
                                                    Выберите правую границу для добавления исторических данных в дневник
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if ($is_chart_changed)
                                    <button wire:click="resetBounds" class="btn text-wrap btn-info">
                                        Вернуться к исходным данным
                                    </button>
                                @endif
                                <button wire:click="saveSugarLevels" class="btn text-wrap btn-primary">
                                    Сохранить данные в дневник
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
