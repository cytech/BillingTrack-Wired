@extends('layouts.master')

@section('content')
    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus();
        });
    </script>

    @if ($editMode)
        {{ html()->modelForm($currency, 'POST', route('currencies.update', $currency->id))->open() }}
    @else
        {{ html()->form('POST', route('currencies.store'))->open() }}
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
                    {{ html()->text('name', null)->class('form-control') }}
                    <p class="form-text text-muted">@lang('bt.help_currency_name')</p>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>@lang('bt.code'): </label>
                            @if ($editMode and $currency->in_use)
                                {{ html()->text('code', null)->class('form-control')->isReadonly() }}
                            @else
                                {{ html()->text('code', null)->class('form-control') }}
                            @endif
                            <p class="form-text text-muted">@lang('bt.help_currency_code')</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>@lang('bt.symbol'): </label>
                            {{ html()->text('symbol', null)->class('form-control') }}
                            <p class="form-text text-muted">@lang('bt.help_currency_symbol')</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label>@lang('bt.symbol_placement'): </label>
                            {{ html()->select('placement', ['before' => trans('bt.before_amount'), 'after'
                            => trans('bt.after_amount')], null)->class( 'form-select') }}
                            <p class="form-text text-muted">@lang('bt.help_currency_symbol_placement')</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.decimal_point'): </label>
                            {{ html()->text('decimal', null)->class('form-control') }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label>@lang('bt.thousands_separator'): </label>
                            {{ html()->text('thousands', null)->class('form-control') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @if ($editMode)
        {{ html()->closeModelForm() }}
    @else
        {{ html()->form()->close() }}
    @endif
@stop
