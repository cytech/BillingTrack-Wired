@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.invoices')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {!! Form::open(['method' => 'GET', 'id' => 'filter']) !!}
                            {!! Form::hidden('client', request('client')) !!}
                        {!! Form::close() !!}
                    </div>
                    <a class="btn btn-secondary rounded border" href="{{ route('utilities.batchprint', ['module' => 'invoices']) }}" title="Batch Print by DateRange"><i
                                class="fa fa-print"></i> @lang('bt.batchprint')</a>
                    <button class="btn btn-primary rounded border"
                            type="button"
                            {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                            onclick="window.livewire.emit('showModal', 'modals.create-module-modal',  'BT\\Modules\\Invoices\\Models\\Invoice', 'create' )"
                    ><i class="fa fa-plus"></i> @lang('bt.create_invoice')
                    </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Invoice'" :reqstatus="$status"/>
            </div>
        </div>
    </section>
@stop
