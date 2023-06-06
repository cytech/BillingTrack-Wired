@extends('layouts.master')

@section('content')

    <script type="text/javascript">
        ready(function () {
            document.getElementById('name').focus()
        })
    </script>

    @if ($editMode)
        {{ html()->modelForm($itemLookup, 'POST', route('itemLookups.update', $itemLookup->id))->open() }}
    @else
        {{ html()->form('POST', route('itemLookups.store'))->open() }}
    @endif

    @include('layouts._alerts')
    <section class="app-content-header">
        <div class="card card-light">
            <div class="card-header">
                <h3 class="card-title"><i
                            class="fa fa-edit fa-fw float-start"></i>
                    @lang('bt.item_lookup_form')
                </h3>
                    <a class="btn btn-warning float-end" href={!! route('itemLookups.index')  !!}><i
                                class="fa fa-ban"></i> @lang('bt.cancel')</a>
                    <button type="submit" class="btn btn-primary float-end"><i
                                class="fa fa-save"></i> @lang('bt.save') </button>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="">@lang('bt.name'): </label>
                    {{ html()->text('name', null)->class('form-control') }}
                </div>
                <div class="mb-3">
                    <label class="">@lang('bt.description'): </label>
                    {{ html()->textarea('description', null)->rows(2)->class('form-control') }}
                </div>
                <div class="mb-3">
                    <label class="">@lang('bt.price'): </label>
                    {{ html()->text('price', (($editMode) ? $itemLookup->formatted_numeric_price: null))->class('form-control') }}
                </div>
                <div class="mb-3">
                    <label class="">@lang('bt.tax_1'): </label>
                    {{ html()->select('tax_rate_id', $taxRates, null)->class('form-select') }}
                </div>
                <div class="mb-3">
                    <label class="">@lang('bt.tax_2'): </label>
                    {{ html()->select('tax_rate_2_id', $taxRates, null)->class('form-select') }}
                </div>
                <div class="mb-3">
                    <label class="">@lang('bt.resource_table'): </label>
                    {{ html()->text('resource_table', (($editMode) ? $itemLookup->resource_table: null))->class('form-control')->isReadonly() }}
                </div>
                <div class="mb-3">
                    <label class="">@lang('bt.resource_id'): </label>
                    {{ html()->text('resource_id', (($editMode) ? $itemLookup->resource_id: null))->class('form-control')->isReadonly() }}
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
