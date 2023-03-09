@extends('layouts.master')

@section('content')
    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus();
        });
    </script>

    @if ($editMode == true)
        {!! Form::model($currency, ['route' => ['currencies.update', $currency->id]]) !!}
    @else
        {!! Form::open(['route' => 'currencies.store']) !!}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.currency_form')</div>
                <a class="btn btn-warning float-end" href={!! route('currencies.index')  !!}><i
                            class="fa fa-ban"></i> @lang('bt.cancel')</a>
                <button type="submit" class="btn btn-primary float-end"><i
                            class="fa fa-save"></i> @lang('bt.save') </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="card card-light">
            <div class="card-body">
                <div class="mb-3">
                    <label>@lang('bt.name'): </label>
                    {!! Form::text('name', null, ['id' => 'name', 'class' => 'form-control']) !!}
                    <p class="form-text text-muted">@lang('bt.help_currency_name')</p>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>@lang('bt.code'): </label>
                            @if ($editMode and $currency->in_use)
                                {!! Form::text('code', null, ['id' => 'code', 'class' => 'form-control',
                                'readonly' => 'readonly']) !!}
                            @else
                                {!! Form::text('code', null, ['id' => 'code', 'class' => 'form-control'])
                                !!}
                            @endif
                            <p class="form-text text-muted">@lang('bt.help_currency_code')</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>@lang('bt.symbol'): </label>
                            {!! Form::text('symbol', null, ['id' => 'symbol', 'class' => 'form-control'])
                            !!}
                            <p class="form-text text-muted">@lang('bt.help_currency_symbol')</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>@lang('bt.symbol_placement'): </label>
                            {!! Form::select('placement', ['before' => trans('bt.before_amount'), 'after'
                            => trans('bt.after_amount')], null, ['class' => 'form-select']) !!}
                            <p class="form-text text-muted">@lang('bt.help_currency_symbol_placement')</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.decimal_point'): </label>
                            {!! Form::text('decimal', null, ['id' => 'decimal', 'class' => 'form-control'])
                            !!}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.thousands_separator'): </label>
                            {!! Form::text('thousands', null, ['id' => 'thousands', 'class' =>
                            'form-control']) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {!! Form::close() !!}
@stop
