<!DOCTYPE html>
<html lang="en">
<!-- For RTL verison -->
<!-- <html lang="en" dir="rtl"> -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('bt.headerTitleText') }}</title>
    <link rel="stylesheet" href="/build/assets/app.css">
    @include('layouts._head')
    <script src="/build/assets/app.js"></script>
        @include('layouts._js_global')
        @yield('javaScript')
</head>
<body class="layout-fixed sidebar-{{$sidebarMode}}">
<div class="wrapper">
    @include('client_center.layouts._header')
    @include('client_center.layouts.sidebar')
    <main class="content-wrapper">
        @yield('content')
    </main>
</div>
<div id="modal-placeholder"></div>
</body>
</html>
