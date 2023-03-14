<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('bt.headerTitleText') }}</title>
    <link rel="stylesheet" href="/build/assets/app.css">
    <script src="/build/assets/app.js"></script>
    @yield('javaScript')
</head>
<style>
    .login-bg {
        background: #c0cffc; /* Old browsers */
        background: -moz-linear-gradient(-45deg, #c0cffc 0%, #b3c0f5 10%, #9da8e7 25%, #9da8ed 37%, #9ea9f2 50%, #7c8ce8 51%, #aec5f6 83%, #c5e1fd 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(-45deg, #c0cffc 0%, #b3c0f5 10%, #9da8e7 25%, #9da8ed 37%, #9ea9f2 50%, #7c8ce8 51%, #aec5f6 83%, #c5e1fd 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(135deg, #c0cffc 0%, #b3c0f5 10%, #9da8e7 25%, #9da8ed 37%, #9ea9f2 50%, #7c8ce8 51%, #aec5f6 83%, #c5e1fd 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    }
</style>
<body class="login-bg">
<!-- Responsive navbar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <div class="align-items-center">
            <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo"
                 class="brand-image img-circle elevation-3 img-size-50"
                 style="opacity: .8">
            <span class="brand-text h1 ms-5">{{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
        </div>
    </div>
</nav>
<!-- Page content-->
<div class="container">
    <div class="text-center mt-5">
        @yield('content')
    </div>
</div>
</body>
</html>
