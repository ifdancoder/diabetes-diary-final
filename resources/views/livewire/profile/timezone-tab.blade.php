<div class="col d-flex flex-column">
    <div class="card-header">
        Часовой пояс
    </div>
    <div class="card-body">
        <h3 class="card-title mt-4">Часовой пояс</h3>
        <div>
            <div class="row g-2">
                <div class="col-sm-9 col-lg-2">
                    <select wire:model="timezone_id" wire:input="updateTimezone" class="form-select">
                        @foreach ($timezones as $timezone)
                            @php
                                $timezone_offset_in_seconds = $timezone->offset;
                                $timezone_offset_hours = floor($timezone_offset_in_seconds / 3600);
                                $timezone_offset_minutes = floor(
                                    ($timezone_offset_in_seconds - $timezone_offset_hours * 3600) / 60,
                                );

                                $formatted_hours = str_pad($timezone_offset_hours, 2, '0', STR_PAD_LEFT);
                                $formatted_minutes = str_pad($timezone_offset_minutes, 2, '0', STR_PAD_LEFT);
                            @endphp
                            <option value="{{ $timezone->id }}" {{ $timezone_id == $timezone->id ? 'selected' : '' }}>
                                {{ $timezone->timezone_name }}
                                (UTC
                                {{ $timezone->offset >= 0 ? '+' : '' }}{{ $formatted_hours }}:{{ $formatted_minutes }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <h3 class="card-title mt-4">Настройка отображения даты и времени</h3>
            <div>
                <label class="form-check form-switch form-switch-lg">
                    <input class="form-check-input" wire:input="updateShowDatetimeType" wire:model="show_datetime_type"
                        type="checkbox" {{ $settings->show_datetime_type ? 'checked' : '' }}>
                    <span class="form-check-label form-check-label-on">Отображаем дату и время в часовом поясе, в
                        котором была сделана запись</span>
                    <span class="form-check-label form-check-label-off">Отображаем дату и время в часовом поясе,
                        указанном в настройках</span>
                </label>
            </div>
        </div>
    </div>
</div>
