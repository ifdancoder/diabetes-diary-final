<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>{{ $title ?? 'Дневник Диабетика' }}</title>
    <link rel="SHORTCUT ICON" href="{{ asset('assets') }}/dist/img/logo/blood.png" type="image/x-icon">

    <link href="{{asset('assets')}}/dist/css/tabler.min.css?1684106062" rel="stylesheet"/>
    <link href="{{asset('assets')}}/dist/css/tabler-flags.min.css?1684106062" rel="stylesheet"/>
    <link href="{{asset('assets')}}/dist/css/tabler-payments.min.css?1684106062" rel="stylesheet"/>
    <link href="{{asset('assets')}}/dist/css/tabler-vendors.min.css?1684106062" rel="stylesheet"/>
    <link href="{{asset('assets')}}/dist/css/demo.min.css?1684106062" rel="stylesheet"/>


    <style>
        @import url('https://rsms.me/inter/inter.css');

        :root {
            --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
        }

        body {
            font-feature-settings: "cv03", "cv04", "cv11";
        }
    </style>
</head>

<body>
    <script src="{{asset('assets')}}/dist/js/demo-theme.min.js?1684106062"></script>

    <div class="page">
        </header>
        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>

    @if (Session::has('danger'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="alert alert-danger">
                {{ Session::get('danger') }}
            </div>
        </div>
    @endif
    @if (Session::has('warning'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="alert alert-warning">
                {{ Session::get('warning') }}
            </div>
        </div>
    @endif
    @if (Session::has('success'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="alert alert-success">
                {{ Session::get('success') }}
            </div>
        </div>
    @endif
    @if (Session::has('info'))
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
            <div class="alert alert-info">
                {{ Session::get('info') }}
            </div>
        </div>
    @endif

    <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
            <div class="row text-center align-items-center ">
            </div>
        </div>
    </footer>

    <script src="{{asset('assets')}}/dist/libs/apexcharts/dist/apexcharts.min.js?1684106062" defer></script>
    <script src="{{asset('assets')}}/dist/libs/jsvectormap/dist/js/jsvectormap.min.js?1684106062" defer></script>
    <script src="{{asset('assets')}}/dist/libs/jsvectormap/dist/maps/world.js?1684106062" defer></script>
    <script src="{{asset('assets')}}/dist/libs/jsvectormap/dist/maps/world-merc.js?1684106062" defer></script>
    <!-- Tabler Core -->
    <script src="{{asset('assets')}}/dist/js/tabler.min.js?1684106062" defer></script>
    <script src="{{asset('assets')}}/dist/js/demo.min.js?1684106062" defer></script>
    
</body>

</html>
