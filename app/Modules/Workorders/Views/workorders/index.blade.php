@extends('layouts.master')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.workorders')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {!! Form::open(['method' => 'GET', 'id' => 'filter', 'class'=>"form-inline"]) !!}
                        <div class="input-group">
                            {!! Form::select('company_profile', $companyProfiles, request('company_profile'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                            {!! Form::select('status', $statuses, request('status'), ['class' => 'filter_options form-select w-auto me-1']) !!}
                        </div>
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
                <livewire:data-tables.module-table :module_type="'Workorder'" :keyedStatuses="$keyedStatuses"/>
            </div>
        </div>
    </section>
@stop
