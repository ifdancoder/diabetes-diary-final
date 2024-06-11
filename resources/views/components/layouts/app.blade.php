<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? 'Дневник Диабетика' }}</title>
    <link rel="SHORTCUT ICON" href="{{ asset('assets') }}/dist/img/logo/blood.png" type="image/x-icon">
    <!-- CSS files -->
    <link href="{{ asset('assets') }}/dist/css/tabler.min.css?1684106062" rel="stylesheet" />
    <link href="{{ asset('assets') }}/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet" />
    <link href="{{ asset('assets') }}/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet" />
    <link href="{{ asset('assets') }}/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet" />
    <link href="{{ asset('assets') }}/dist/css/demo.min.css?1684106062" rel="stylesheet" />
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    @livewireStyles
</head>

<body class=" layout-fluid ">
    @livewire('wire-elements-modal')

    <script src="{{ asset('assets') }}/dist/js/demo-theme.min.js?1684106062"></script>
    <div class="page">
        <livewire:l-header />
        <livewire:l-sidebar />
        <div class="page-wrapper">
            <div class="page-body m-0">
                <div class="container-xl">
                    <div class="row g-0 m-0">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center ">
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('assets') }}/dist/js/tabler.min.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/js/demo.min.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/js/autosize.js" defer></script>

    <!-- Libs JS -->
    <script src="{{ asset('assets') }}/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/libs/nouislider/dist/nouislider.min.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/libs/litepicker/dist/litepicker.js?1684106062" defer></script>
    <script src="{{ asset('assets') }}/dist/libs/fslightbox/index.js?1695847769" defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


    <script src="{{ asset('assets') }}/dist/libs/tom-select/dist/js/tom-select.base.min.js?1684106062" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/ru.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.5.1/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/date-fns/1.30.1/date_fns.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chartjs-plugin-zoom/2.0.1/chartjs-plugin-zoom.min.js"></script>
    <script src="	https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.27.0/moment-with-locales.min.js"></script>

    <script>
        function appendScriptToParent(parent_id) {
            const parent = document.getElementById(parent_id);

            const script = document.createElement('script');
            const script_src = "https://telegram.org/js/telegram-widget.js?22";
            const data_telegram_login = "diabetes_diary_bot";
            const data_size = "large";
            const data_userpic = "false";
            const data_auth_url = "{{ route('tg.connect') }}";
            const data_request_access = "write";

            script.setAttribute('data-telegram-login', data_telegram_login);
            script.setAttribute('data-size', data_size);
            script.setAttribute('data-userpic', data_userpic);
            script.setAttribute('data-auth-url', data_auth_url);
            script.setAttribute('data-request-access', data_request_access);
            script.src = script_src;

            parent.appendChild(script);
        }
    </script>

    @stack('scripts')

    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('setUrl', param => {
                window.history.replaceState(null, null, param);
            });
        });

        function timestamp_to_formatted(timestamp) {
            const date = new Date(timestamp);
            return date.getFullYear() + '-' + (date.getMonth() + 1) + '-' + date.getDate() + ' ' + date.getHours() + ':' +
                date.getMinutes()
        }

        function prepare_data(data) {
            return data.map(obj => {
                return {
                    x: obj.x * 1000,
                    y: obj.y
                };
            });
        }

        function call_flatpickr(id) {
            flatpickr('#' + id, {
                locale: 'ru',
                enableTime: true,
                dateFormat: "Y-m-d\TH:i",
                time_24hr: true
            });
        }

        function call_flatpickr_time(id) {
            flatpickr('#' + id, {
                locale: 'ru',
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: true
            });
        }

        function livewireOnPointClick(e) {
            Livewire.dispatch('pointClick', {point: e});
        }

        function makeChart(id) {
            const ctx = document.getElementById(id).getContext('2d');

            let chart = new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: []
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'hour',
                                displayFormats: {
                                    'hour': 'HH:mm'
                                }
                            }
                        }
                    },
                    plugins: {
                        zoom: {
                            zoom: {
                                wheel: {
                                    enabled: true,
                                },
                                pinch: {
                                    enabled: true
                                },
                                mode: 'x',
                            }
                        }
                    },
                    onClick(e) {
                        const activePoints = chart.getElementsAtEventForMode(e, 'point', {
                            intersect: true
                        }, false)
                        const [{
                            index
                        }] = activePoints;
                        livewireOnPointClick(index);
                    }
                }
            });
            return chart;
        }

        function deleteAllLeft(first_index) {
            if (first_index === 0) return;
            chart.data.datasets[0].data.splice(0, first_index);
            chart.update();
        }

        function deleteAllRight(last_index) {
            if (last_index === 0) return;
            chart.data.datasets[0].data.splice(last_index + 1, chart.data.datasets[0].data.length - last_index);
            chart.update();
        }

        function addLineIntoChart(chart, label, color, data = []) {
            chart.data.datasets.push({
                label: label,
                fill: false,
                borderColor: color,
                data: data
            });
            chart.update();
        }

        function updateLineChart(chart, new_data, line_index = 1) {
            new_data = new_data.map((obj) => {
                return {
                    x: Date.parse(obj.datetime),
                    y: obj.val
                }
            });

            chart.data.datasets[line_index].data = new_data;
            chart.update();
        }
    </script>

    @livewireScripts
</body>

</html>
