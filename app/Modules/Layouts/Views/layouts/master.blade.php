<!DOCTYPE html>
<html lang="en" data-bs-theme="{{$headBackground}}">
<!-- For RTL verison -->
<!-- <html lang="en" dir="rtl"> -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('bt.headerTitleText') }}</title>
    <link rel="stylesheet" href="/build/assets/app.css">
    @include('layouts._head')
{{--    <script defer src="{{ asset('plugins/alpinejs/cdn.min.js') }}"></script>--}}
    <script src="/build/assets/app.js"></script>
    {{--    @vite(['resources/js/app.js'])--}}
    @include('layouts._js_global')

    @yield('javaScript')
{{--    @livewireStyles--}}
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-mini sidebar-{{$sidebarMode}}">
<div class="app-wrapper">
    @include('layouts._header')
    @include('layouts.sidebar')
    <main class="app-main">
        @yield('content')
    </main>
</div>
<div id="modal-placeholder"></div>
<a href="#" class="back-to-top">
    <i class="fa fa-chevron-circle-up"></i>
</a>
@stack('scripts')
<livewire:modals/>
{{--@livewireScripts--}}
</body>
</html>

<script>
    // start for adminlte v4 sidebar scrolling
    const SELECTOR_SIDEBAR_WRAPPER = ".sidebar-wrapper";
    let sbartheme
    if ("{!! str_ends_with($headBackground, 'light') !!}") {
        sbartheme = "os-theme-dark"
    } else {
        sbartheme = "os-theme-light"
    }
    const Default = {
        scrollbarTheme: sbartheme,
        scrollbarAutoHide: "leave",
        scrollbarClickScroll: true,
    };

    document.addEventListener("DOMContentLoaded", function() {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
        if (
            sidebarWrapper &&
            typeof OverlayScrollbarsGlobal !== "undefined"
        ) {
            OverlayScrollbarsGlobal(sidebarWrapper, {
                scrollbars: {
                    theme: Default.scrollbarTheme,
                    autoHide: Default.scrollbarAutoHide,
                    clickScroll: Default.scrollbarClickScroll,
                },
            });
        }
    });

    // end for adminlte v4 sidebar scrolling

    // livewire modals-bs5.js
    let modalsElement = document.getElementById('laravel-livewire-modals');
    let lwModal = bootstrap.Modal.getOrCreateInstance(modalsElement);

    // example
    // modalsElement.addEventListener('shown.bs.modal', (e) => {
    //     let elt = e.target.querySelectorAll('*[autofocus],*[autofocus=true],*[autofocus="autofocus"]');
    //     if( elt && elt.length > 0 ) {
    //         elt[0].focus();
    //     }
    // });

    modalsElement.addEventListener('shown.bs.modal', (e) => {
        let tinput = e.target.querySelector('input[type=text]:not(:read-only)')
        if (tinput) tinput.focus()
    })

    modalsElement.addEventListener('hidden.bs.modal', () => {
        window.Livewire.dispatch('resetModal');
    })

    window.Livewire.on('showBootstrapModal', () => {
        lwModal.show()
    });

    window.Livewire.on('hideModal', () => {
        lwModal.hide()
    });
</script>
