<div class="col d-flex flex-column">
    <div class="card-header">
        Настройки уведомлений
    </div>
    <div class="card-body">
        <h3 class="card-title mt-4">Уведомления о входах и выходах</h3>
        <div>
            <label class="form-check form-switch form-switch-lg">
                <input class="form-check-input" wire:input="updateNotifications" wire:model="log_in_out_notifications"
                    name="log_in_out_notifications" type="checkbox"
                    {{ $settings->log_in_out_notifications ? 'checked' : '' }}>
                <span class="form-check-label form-check-label-on">Вас уведомляют о входах/выходах</span>
                <span class="form-check-label form-check-label-off">Вас не уведомляют о входах/выходах</span>
            </label>
        </div>
        <h3 class="card-title mt-4">Уведомления при длительном отсутствии новых записей</h3>
        <div>
            <label class="form-check form-switch form-switch-lg">
                <input class="form-check-input" wire:input="updateNotifications"
                    wire:model="reminder_notifications" name="reminder_notifications" type="checkbox"
                    {{ $settings->reminder_notifications ? 'checked' : '' }}>
                <span class="form-check-label form-check-label-on">Вас уведомляют о длительном отсутствии новых
                    записей</span>
                <span class="form-check-label form-check-label-off">Вас не уведомляют о длительном отсутствии новых
                    записей</span>
            </label>
        </div>
        <h3 class="card-title mt-4">Уведомления посредством социальных сетей</h3>
        <div>
            <label class="form-check form-switch form-switch-lg">
                <input class="form-check-input" wire:input="updateNotifications" wire:model="notifications_from_social"
                    name="notifications_from_social" type="checkbox"
                    {{ $settings->notifications_from_social ? 'checked' : '' }}>
                <span class="form-check-label form-check-label-on">Вас уведомляют посредством социальных сетей</span>
                <span class="form-check-label form-check-label-off">Вас не уведомляют посредством социальных
                    сетей</span>
            </label>
        </div>
    </div>
</div>
