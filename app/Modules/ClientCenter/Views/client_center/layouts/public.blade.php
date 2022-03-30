<!DOCTYPE html>
<html class="public-layout">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="/css/app.css">
    <script src="/js/app.js"></script>
    <title>{{ config('bt.headerTitleText') }}</title>
    @include('layouts._head')

    @include('layouts._js_global')

    @yield('head')

    @yield('javaScript')
</head>
<body class="layout-boxed sidebar-mini ">
<div class="wrapper">
    <header class="main-header navbar navbar-expand navbar-{{ $headClass }} bg-{{ $headBackground }} border-bottom">
        <div class="brand-container bg-{{ $headBackground }} ">
           <div class="brand-link ">
            <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo"
                 class="brand-image img-circle elevation-3 img-sm pe-1"
                 style="opacity: .8">
            <span class="brand-text fw-light">{{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
        </div>
        </div>
    </header>
    <div class="content-wrapper content-wrapper-public mt-2">
        @yield('content')
    </div>
</div>
<div id="modal-placeholder"></div>
</body>
</html>
