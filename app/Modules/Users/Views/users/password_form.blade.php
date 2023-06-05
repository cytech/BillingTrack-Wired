@extends('layouts.master')

@section('content')
    <script type="text/javascript">
        ready(function () {
            document.getElementById('password').focus()
        });
    </script>

    {{ html()->form('POST', route('users.password.update', $user->id))->open() }}

    <section class="app-content-header">
        <h3 class="float-start px-3">
            @lang('bt.reset_password'): {{ $user->name }} ({{ $user->email }})
        </h3>
        <a class="btn btn-warning float-end" href={!! route('users.index')  !!}><i
                    class="fa fa-ban"></i> @lang('bt.cancel')</a>
        <button type="submit" class="btn btn-primary float-end"><i
                    class="fa fa-user-lock"></i> @lang('bt.reset_password') </button>
        <div class="clearfix"></div>
    </section>

    <section class="container-fluid">
        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                <div class="mb-3">
                    <label>@lang('bt.password'): </label>
                    {{ html()->password('password')->class('form-control') }}
                </div>
                <div class="mb-3">
                    <label>@lang('bt.password_confirmation'): </label>
                    {{ html()->password('password_confirmation')->class('form-control') }}
                </div>
            </div>
        </div>
    </section>
    {{ html()->form()->close() }}
@stop
