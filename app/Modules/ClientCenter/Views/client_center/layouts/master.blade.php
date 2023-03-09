<!DOCTYPE html>
<html lang="en" data-bs-theme="purple-light">
<!-- For RTL verison -->
<!-- <html lang="en" dir="rtl"> -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('bt.headerTitleText') }}</title>
    <link rel="stylesheet" href="/build/assets/app.css">
    @include('layouts._head')
    <script src="/build/assets/app.js"></script>
        @include('layouts._js_global')
        @yield('javaScript')
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-{{$sidebarMode}}">
<div class="app-wrapper">
    @include('client_center.layouts._header')
    @include('client_center.layouts.sidebar')
    <main class="app-main">
        @yield('content')
    </main>
</div>
<div id="modal-placeholder"></div>
</body>
</html>
