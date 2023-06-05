@extends('layouts.master')

@section('content')

    @if ($editMode)
        {{ html()->modelForm($customField, 'POST', route('customFields.update', $customField->id))->open() }}
    @else
        {{ html()->form('POST', route('customFields.store'))->open() }}
    @endif

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.custom_field_form')</div>
                <a class="btn btn-warning float-end" href={!! route('customFields.index')  !!}><i
                            class="fa fa-ban"></i> @lang('bt.cancel')</a>
                <button type="submit" class="btn btn-primary float-end"><i
                            class="fa fa-save"></i> @lang('bt.save') </button>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                <div class="mb-3">
                    <label>@lang('bt.table_name'): </label>
                    @if ($editMode)
                        {{ html()->text('tbl_name', $tableNames[$customField->tbl_name])->class(['form-control'])->isReadonly() }}
                    @else
                        {{ html()->select('tbl_name', $tableNames, null)->class(['form-select'])}}
                    @endif
                </div>
                <div class="mb-3">
                    <label>@lang('bt.field_label'): </label>
                    {{ html()->text('field_label', null)->class(['form-control']) }}
                </div>
                <div class="mb-3">
                    <label>@lang('bt.field_type'): </label>
                    {{ html()->select('field_type', $fieldTypes, null)->class(['form-select'])}}
                </div>
                <div class="mb-3">
                    <label>@lang('bt.field_meta'): </label>
                    {{ html()->text('field_meta', null)->class(['form-control']) }}
                    <span class="form-text text-muted">@lang('bt.field_meta_description')</span>
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
