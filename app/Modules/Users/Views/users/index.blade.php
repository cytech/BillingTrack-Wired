@extends('layouts.master')

@section('content')

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.users')</div>
                @if (!config('app.demo'))
                    <div class="float-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary" data-bs-toggle="dropdown"
                                    aria-expanded="false"><i
                                        class="fa fa-plus"></i>
                                @lang('bt.create_user')
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item"
                                   href="{{ route('users.create', ['admin']) }}">@lang('bt.admin_account')</a>
                                <a class="dropdown-item"
                                   href="{{ route('users.create', ['user']) }}">@lang('bt.user_account')</a>
                                <a class="dropdown-item"
                                   href="{{ route('users.create', ['client']) }}">@lang('bt.client_account')</a>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'User'"/>
            </div>
        </div>
    </section>
    @else
        <br><br>
        User configuration is disabled in the demo.
    @endif
@endsection
