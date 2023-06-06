@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.custom_fields')</div>
                <div class="float-end">
                    <a href="{{ route('customFields.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus"></i> @lang('bt.create_customfield')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="card card-light">
            <div class="card-body">
                <table class="table table-striped datatable">
                    <thead class="bg-body lwtable">
                    <tr>
                        <th>{!! trans('bt.table_name') !!}</th>
                        <th>{!! trans('bt.column_name') !!}</th>
                        <th>{!! trans('bt.field_label') !!}</th>
                        <th>{!! trans('bt.field_type') !!}</th>
                        <th>@lang('bt.options')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($customFields as $customField)
                        <tr>
                            <td>{{ $tableNames[$customField->tbl_name] }}</td>
                            <td>{{ $customField->column_name }}</td>
                            <td>{{ $customField->field_label }}</td>
                            <td>{{ $customField->field_type }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-toggle="dropdown">
                                        @lang('bt.options')
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item"
                                           href="{{ route('customFields.edit', [$customField->id]) }}"><i
                                                    class="fa fa-edit"></i> @lang('bt.edit')</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"
                                           onclick="swalConfirm('@lang('bt.delete_record_warning')', '', '{{ route('customFields.delete', [$customField->id]) }}');"><i
                                                    class="fa fa-trash-alt text-danger"></i> @lang('bt.delete')</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="float-end">
            {!! $customFields->appends(request()->except('page'))->render() !!}
        </div>
    </section>
@stop
