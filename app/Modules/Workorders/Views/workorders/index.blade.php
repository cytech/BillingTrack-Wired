@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.workorders')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {!! Form::open(['method' => 'GET', 'id' => 'filter']) !!}
                            {!! Form::hidden('client', request('client')) !!}
                        {!! Form::close() !!}
                        <a class="btn btn-secondary rounded border" href="{{ route('utilities.batchprint', ['module' => 'workorders']) }}" title="Batch Print by DateRange"><i
                                    class="fa fa-print"></i> @lang('bt.batchprint')</a>
                        <button class="btn btn-primary rounded border"
                                type="button"
                                {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                                onclick="window.livewire.emit('showModal', 'modals.create-module-modal',  'BT\\Modules\\Workorders\\Models\\Workorder', 'create' )"
                        ><i class="fa fa-plus"></i> @lang('bt.create_workorder')
                        </button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="'Workorder'"  :reqstatus="$status"/>
            </div>
        </div>
    </section>
@stop
