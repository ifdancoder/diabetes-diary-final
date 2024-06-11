<aside class="navbar navbar-vertical navbar-expand-lg " style="z-index:1; display: inline-table; height: 100%;"
    data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
            <a href="{{ route('home') }}" class="d-flex justify-content-start">
                <img src="{{ asset('assets') }}/dist/img/logo/blood.png" width="32" height="32"
                    alt="Дневник диабетика">
            </a>
        </h1>
        <div class="d-lg-none col-6">
            <div class="nav-item dropdown">
                <a class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                    <div class="row">
                        <div class="col">
                            <div class="small text-secondary text-center text-wrap">
                                {{ $user->last_name . ' ' . $user->first_name }}
                            </div>
                            <div class="text-center text-wrap">
                                {{ $user->email }}
                            </div>
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('profile') }}" class="dropdown-item">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                            <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                        </svg>
                        &nbspПрофиль
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        @method('POST')
                        <button type="submit" class="dropdown-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-door-exit">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M13 12v.01" />
                                <path d="M3 21h18" />
                                <path d="M5 21v-16a2 2 0 0 1 2 -2h7.5m2.5 10.5v7.5" />
                                <path d="M14 7h7m-3 -3l3 3l-3 3" />
                            </svg>
                            &nbspВыйти
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav">
                <li class="nav-item {{ $current_tab == 'home' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('home') }}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0"></path>
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7"></path>
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6"></path>
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Домашняя страница
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $current_tab == 'basal' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('basal') }}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-adjustments-alt">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 8h4v4h-4z" />
                                <path d="M6 4l0 4" />
                                <path d="M6 12l0 8" />
                                <path d="M10 14h4v4h-4z" />
                                <path d="M12 4l0 10" />
                                <path d="M12 18l0 2" />
                                <path d="M16 5h4v4h-4z" />
                                <path d="M18 4l0 1" />
                                <path d="M18 9l0 11" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Настройка базальных скоростей
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $current_tab == 'records' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('records') }}">
                        <span
                            class="nav-link-icon d-md-none d-lg-inline-block"><!-- Download SVG icon from http://tabler-icons.io/i/home -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-list">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 6l11 0" />
                                <path d="M9 12l11 0" />
                                <path d="M9 18l11 0" />
                                <path d="M5 6l0 .01" />
                                <path d="M5 12l0 .01" />
                                <path d="M5 18l0 .01" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Просмотр существующих записей
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $current_tab == 'cgm' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('cgm') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-autofit-up">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 4h-6a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h8" />
                                <path d="M18 20v-17" />
                                <path d="M15 6l3 -3l3 3" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Загрузить записи с CGM
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $current_tab == 'experiments' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('experiments') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-microscope">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 21h14" />
                                <path d="M6 18h2" />
                                <path d="M7 18v3" />
                                <path d="M9 11l3 3l6 -6l-3 -3z" />
                                <path d="M10.5 12.5l-1.5 1.5" />
                                <path d="M17 3l3 3" />
                                <path d="M12 21a6 6 0 0 0 3.715 -10.712" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Сеансы прогнозирования
                        </span>
                    </a>
                </li>
                <li class="nav-item {{ $current_tab == 'help' ? 'active' : '' }}">
                    <a class="nav-link" href="{{ route('help') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-info-circle">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 9h.01" />
                                <path d="M11 12h1v4h1" />
                            </svg>
                        </span>
                        <span class="nav-link-title">
                            Справка
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</aside>
