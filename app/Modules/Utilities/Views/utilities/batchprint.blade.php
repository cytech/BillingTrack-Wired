@extends('layouts.master')

@section('javaScript')
    @include('layouts._daterangepicker')
@stop

@section('content')
    @include('layouts._alerts')

    <section class="app-content-header">
        <section class="container-fluid">
            <div class="col-sm-12 mb-3">
                <div class="fs-3 float-start">@lang('bt.batchprint')</div>
                {{ html()->form('POST', route('utilities.batchprint'))->class('form-horizontal')->open() }}
                <div class="btn-group float-end">
                    <button type="submit" class="btn btn-primary float-end"><i
                                class="fa fa-save"></i> @lang('bt.process') </button>
                </div>
                <div class="clearfix"></div>
            </div>
        </section>
        <section class="content">
            <div class="card card-light">
                <div class="card-header">
                    <h3 class="card-title">
                        @lang('bt.criteria_batchprint') of @lang('bt.'. $module)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="col-md-4 mb-3">
                        {{ html()->hidden('batch_type', $module) }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">@lang('bt.date_range'):</label>
                        {{ html()->hidden('from_date', null) }}
                        {{ html()->hidden('to_date', null) }}
                        <div class="input-group">
                            {{ html()->text('date_range', null)->isReadonly()->class(['form-control']) }}
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i> </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <span class="form-text text-muted">@lang('bt.criteria_batchprint_list')</span>
                    </div>
                    <script>
                        document.getElementById('from_date').value = '{{ \Carbon\Carbon::now()->format('Y-m-d') }}'
                        document.getElementById('to_date').value = '{{ \Carbon\Carbon::now()->format('Y-m-d') }}'
                    </script>
                </div>
            </div>
            {{ html()->form()->close() }}
        </section>
@stop
