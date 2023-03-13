@extends('layouts.master')

@section('content')

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.groups')</div>
                <div class="float-end">
                    <a href="{{ route('groups.create') }}" class="btn btn-primary"><i
                                class="fa fa-plus"></i> @lang('bt.create_group')</a>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        @include('layouts._alerts')
        <div class=" card card-light">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>{!! Sortable::link('name', trans('bt.name')) !!}</th>
                        <th>{!! Sortable::link('format', trans('bt.format')) !!}</th>
                        <th>{!! Sortable::link('next_id', trans('bt.next_number')) !!}</th>
                        <th>{!! Sortable::link('left_pad', trans('bt.left_pad')) !!}</th>
                        <th>{!! Sortable::link('reset_number', trans('bt.reset_number')) !!}</th>
                        <th>@lang('bt.options')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($groups as $group)
                        <tr>
                            <td>{{ $group->name }}</td>
                            <td>{{ $group->format }}</td>
                            <td>{{ $group->next_id }}</td>
                            <td>{{ $group->left_pad }}</td>
                            <td>{{ $resetNumberOptions[$group->reset_number] }}</td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                            data-bs-toggle="dropdown">
                                        @lang('bt.options')
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="{{ route('groups.edit', [$group->id]) }}"><i
                                                    class="fa fa-edit"></i> @lang('bt.edit')</a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="#"
                                           onclick="swalConfirm('@lang('bt.delete_record_warning')', '', '{{ route('groups.delete', [$group->id]) }}');"><i
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
            {!! $groups->appends(request()->except('page'))->render() !!}
        </div>
    </section>

@stop
