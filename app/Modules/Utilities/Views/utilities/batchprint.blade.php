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
                {!! Form::open(['route' => 'utilities.batchprint', 'class'=>'form-horizontal']) !!}
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
                        @lang('bt.criteria_batchprint') of @lang('bt.'.$module)
                    </h3>
                </div>
                <div class="card-body">
                    <div class="col-md-4 mb-3">
                        {!! Form::hidden('batch_type', $module) !!}
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">@lang('bt.date_range'):</label>
                        {!! Form::hidden('from_date', null, ['id' => 'from_date']) !!}
                        {!! Form::hidden('to_date', null, ['id' => 'to_date']) !!}
                        <div class="input-group">
                            {!! Form::text('date_range', null, ['id' => 'date_range', 'class' => 'form-control', 'readonly' => 'readonly']) !!}
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
            {!! Form::close() !!}
        </section>
@stop
