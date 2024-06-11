<div>
    <div class="page-body">
        <div class="card">
            <div class="row">
                <div class="col-md-3 d-sm-block border-end">
                    <div class="card-header">
                        Профиль
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-transparent">
                            <button type="button" wire:click="changeTab('basic')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'basic' ? 'active' : '' }}">Основное</button
                                type="button">
                            <button type="button" wire:click="changeTab('notifications')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'notifications' ? 'active' : '' }}">Уведомления</button
                                type="button">
                            <button type="button" wire:click="changeTab('social')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'social' ? 'active' : '' }}">Социальные
                                сети</button type="button">
                            <button type="button" wire:click="changeTab('timezone')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'timezone' ? 'active' : '' }}">Часовой пояс</button type="button">
                        </div>
                        <h4 class="subheader mt-4">Интеграция с нейронной сетью</h4>
                        <div class="list-group list-group-transparent">
                            <button type="button" wire:click="changeTab('connection')"
                                class="list-group-item list-group-item-action d-flex align-items-center {{ $current_tab === 'connection' ? 'active' : '' }}">Связаться
                                с администратором</button type="button">
                        </div>
                    </div>
                </div>
                @if ($current_tab === 'basic')
                    <livewire:profile.basic />
                @elseif($current_tab === 'notifications')
                    <livewire:profile.notifications />
                @elseif($current_tab === 'social')
                    <livewire:profile.social />
                @elseif($current_tab === 'connection')
                    <livewire:profile.connection />
                @elseif($current_tab === 'timezone')
                    <livewire:profile.timezone-tab />
                @endif
            </div>
        </div>
    </div>
</div>
