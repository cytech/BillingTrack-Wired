<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>@lang('bt.welcome')</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/build/assets/app.css">
    <script src="/build/assets/app.js"></script>
    <link href="{{ asset('favicon.png') }}" rel="icon" type="image/png">
    @if (file_exists(base_path('custom/custom.css')))
        <link href="{{ asset('custom/custom.css') }}" rel="stylesheet" type="text/css"/>
    @endif
</head>
<style>
    .login-bg {
        background: #c0cffc; /* Old browsers */
        background: -moz-linear-gradient(-45deg, #c0cffc 0%, #b3c0f5 10%, #9da8e7 25%, #9da8ed 37%, #9ea9f2 50%, #7c8ce8 51%, #aec5f6 83%, #c5e1fd 100%); /* FF3.6-15 */
        background: -webkit-linear-gradient(-45deg, #c0cffc 0%, #b3c0f5 10%, #9da8e7 25%, #9da8ed 37%, #9ea9f2 50%, #7c8ce8 51%, #aec5f6 83%, #c5e1fd 100%); /* Chrome10-25,Safari5.1-6 */
        background: linear-gradient(135deg, #c0cffc 0%, #b3c0f5 10%, #9da8e7 25%, #9da8ed 37%, #9ea9f2 50%, #7c8ce8 51%, #aec5f6 83%, #c5e1fd 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    }
</style>
<body class="login-page login-bg">
@if(!config('app.demo'))
    <div class="brand-link mb-5">
        <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo" class="img-size-64 mb-5"
             style="opacity: .8">
        <span class="display-3 ms-3"> {{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
    </div>
@else
    <div class="brand-link mb-5">
        <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo" class="img-size-64 mb-5"
             style="opacity: .8">
        <span class="display-3 ms-3"> {{ config('bt.headerTitleText', config('app.name','BillingTrack')) }} Live Demo</span>
    </div>
@endif
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">{{ __('bt.account_login') }}</div>
                <div class="card-body">
                    {!! Form::open() !!}
                    @csrf
                    <div class="form-group row mb-3">
                        <label for="email"
                               class="col-sm-4 col-form-label text-md-right">{{ __('bt.email_address') }}</label>
                        <div class="col-md-8">
                            <input id="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror" name="email"
                                   value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row mb-3">
                        <label for="password"
                               class="col-md-4 col-form-label text-md-right">{{ __('bt.password') }}</label>
                        <div class="col-md-8">
                            <input id="password" type="password"
                                   class="form-control @error('password') is-invalid @enderror"
                                   name="password" required autocomplete="current-password">
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                    </div>
                    @if(!config('app.demo'))

                        <div class="form-group row mb-5">
                            <div class="col-md-8 offset-md-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="hidden" name="remember_me" value="0">
                                        <input class="form-check-input" type="checkbox" name="remember_me"
                                               value="1"> @lang('bt.remember_me')
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group row mb-4">
                        <div class="col-md-8 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('bt.sign_in') }}
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    @if(config('app.demo'))
                        <div class="row justify-content-center mt-5">
                            <b>Demo Login - Admin</b>
                            <br>
                            Email = demoadmin@example.com
                            <br>
                            Password = secret
                            <br>
                            <b>Demo Login - User</b>
                            <br>
                            Email = demouser@example.com
                            <br>
                            Password = secret
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', () => {
        document.getElementById('email').focus()
    });
</script>
</body>
</html>
