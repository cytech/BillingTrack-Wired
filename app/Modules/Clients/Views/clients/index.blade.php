@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.clients')</div>
                <div class="btn-group float-end">
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
                <livewire:data-tables.module-table :module_type="'Client'" :module_fullname="$modulefullname"
                                                   :keyedStatuses="$keyedStatuses"/>
            </div>
        </div>
    </section>
@stop
