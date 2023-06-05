@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.' . strtolower($module_type) . 's')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        {{ html()->form('GET', route('documents.index'))->attribute('id', 'filter')->open() }}
                            {{ html()->hidden('client', request('client')) }}
                        {{ html()->form()->close() }}
                    </div>
                    @if($module_type != 'Recurringinvoice')
                    <a class="btn btn-secondary rounded border" href="{{ route('utilities.batchprint', ['module' => strtolower($module_type) . 's']) }}" title="Batch Print by DateRange"><i
                                class="fa fa-print"></i> @lang('bt.batchprint')</a>
                    @endif
                    <button class="btn btn-primary rounded border"
                            type="button"
                            {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                            onclick="window.livewire.emit('showModal', 'modals.create-module-modal',  '{{$modulefullname}}', '{{$module_type}}', 'create' )"
                    ><i class="fa fa-plus"></i> @lang('bt.create_' . strtolower($module_type))
                    </button>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card ">
            <div class="card-body">
                <livewire:data-tables.module-table :module_type="$module_type"  :reqstatus="$status" :clientid="$client"/>
            </div>
        </div>
    </section>
@stop
