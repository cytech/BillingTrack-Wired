@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.employees')</div>
                <div class="btn-group float-end">
                    <a href="{{ route('employees.index', ['status' => 'active']) }}"
                       class="btn btn-secondary @if ($status == 'active') active @endif">@lang('bt.active')</a>
                    <a href="{{ route('employees.index', ['status' => 'inactive']) }}"
                       class="btn btn-secondary @if ($status == 'inactive') active @endif">@lang('bt.inactive')</a>
                    <a href="{{ route('employees.index') }}"
                       class="btn btn-secondary @if ($status == 'all') active @endif">@lang('bt.all')</a>
                    <a href="{{ route('employees.create') }}" class="btn btn-primary "><i
                                class="fa fa-plus"></i> @lang('bt.create_employee')</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Employee'"/>
            </div>
        </div>
    </section>
@stop
