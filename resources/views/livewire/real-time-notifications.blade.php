<div wire:poll.500ms class="nav-item dropdown d-none d-md-flex me-3">
    <a wire:ignore.self href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
        aria-label="Show notifications">
        <!-- Download SVG icon from http://tabler-icons.io/i/bell -->
        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
            stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
            <path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6"></path>
            <path d="M9 17v1a3 3 0 0 0 6 0v-1"></path>
        </svg>
        <span class="{{ $user->unreadNotifications->count() > 0 ? 'badge bg-red' : '' }}"></span>
    </a>
    <div wire:ignore.self class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Уведомления</h3>
                @if ($user->unreadNotifications->count() > 0)
                    <div class="card-actions">
                        <button class="btn" wire:click="markAsRead">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-checks">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 12l5 5l10 -10" />
                                <path d="M2 12l5 5m5 -5l5 -5" />
                            </svg>
                            Пометить, как прочитанные
                        </button>
                    </div>
                @endif
            </div>
            <div class="list-group list-group-flush list-group-hoverable">
                @if ($user->unreadNotifications->count() == 0)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <div class="col text-truncate">У вас нет непрочитанных уведомлений</div>
                            </div>
                        </div>
                    </div>
                @endif
                @foreach ($user->unreadNotifications as $notification)
                    <div class="list-group-item">
                        <div class="row align-items-center">
                            <div class="col-auto"><span
                                    class="status-dot {{ $notification->data['color'] }} d-block"></span></div>
                            <div class="col text-wrap">
                                <a href="{{ $notification->data['link'] }}"
                                    class="text-body d-block">{!! $notification->data['header'] !!}</a>
                                <div class="d-block text-secondary text-wrap mt-n1">
                                    {!! $notification->data['description'] !!}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>
