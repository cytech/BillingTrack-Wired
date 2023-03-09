<!DOCTYPE html>
<html class="public-layout" data-bs-theme="purple-light">
<head>
    <meta charset="UTF-8">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="/build/assets/app.css">
    <script src="/build/assets/app.js"></script>
    <title>{{ config('bt.headerTitleText') }}</title>
    @include('layouts._head')

    @include('layouts._js_global')

    @yield('head')

    @yield('javaScript')
</head>
<body class="layout-fixed">
<div class="app-wrapper">
    <div class="app-header ">
        <div class="sidebar-brand bg-body">
           <div class="brand-link ">
            <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo"
                 class="brand-image img-circle elevation-3 img-sm pe-1">
            <span class="brand-text">{{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
        </div>
        </div>
    </div>
    <div class="app-main">
        @yield('content')
    </div>
</div>
<div id="modal-placeholder"></div>
<a href="#" class="back-to-top">
    <i class="fa fa-chevron-circle-up"></i>
</a>
</body>
</html>
