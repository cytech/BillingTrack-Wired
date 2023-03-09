@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.clients')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        <a href="{{ route('clients.index', ['status' => 'active']) }}"
                           class="btn btn-secondary rounded-3 border  @if ($status == 'active') active @endif">@lang('bt.active')</a>
                        <a href="{{ route('clients.index', ['status' => 'inactive']) }}"
                           class="btn btn-secondary rounded-3 border @if ($status == 'inactive') active @endif">@lang('bt.inactive')</a>
                        <a href="{{ route('clients.index') }}"
                           class="btn btn-secondary rounded-3 border @if ($status == 'all') active @endif">@lang('bt.all')</a>
                        <a href="{{ route('clients.index', ['status' => 'company']) }}"
                           class="btn btn-secondary rounded-3 border @if ($status == 'company') active @endif">@lang('bt.company')</a>
                        <a href="{{ route('clients.index', ['status' => 'individual']) }}"
                           class="btn btn-secondary rounded-3 border @if ($status == 'individual') active @endif">@lang('bt.individual')</a>
                    </div>
                    <a href="{{ route('clients.create') }}" class="btn btn-primary rounded-3 border"><i
                                class="fa fa-plus"></i> @lang('bt.create_client')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Client'"/>
            </div>
        </div>
    </section>
@stop
