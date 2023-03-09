@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.acl_manage')</div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="content">
        <div class="card">
            <div class="card-header h3">@lang('bt.acl_roles')
                @can('create_roles')
                    <div style="float:right;padding-right:20px;"><a href="{{ route('users.roles.create') }}"
                                                                    title="@lang('bt.acl_add_role')"
                                                                    class="btn btn-primary "><i
                                    class="fa fa-plus"></i> @lang('bt.acl_add_role')</a>
                    </div>
                @endcan
            </div>
            <div class="card-body">
                @if (count($roles))
                    <table id="roles" class="table table-striped table-hover table-responsive-sm table-sm compact">
                        <thead>
                        <tr>
                            <th>@lang('bt.id')</th>
                            <th>@lang('bt.name')</th>
                            <th>@lang('bt.description')</th>
                            <th>@lang('bt.acl_guard_name')</th>
                            <th>@lang('bt.created')</th>
                            <th>@lang('bt.updated')</th>
                            <th>@lang('bt.action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td>{{ $role->description }}</td>
                                <td>{{ $role->guard_name }}</td>
                                <td>{{ $role->created_at->toFormattedDateString() }}</td>
                                <td>{{ $role->updated_at->toFormattedDateString() }}</td>
                                <td>
                                    {{-- <a href="{{ route('roles.show', ['role' => $role->id]) }}" title="View Role Details"><i class="fa fa-info-circle fa-2x"></i></a>&nbsp;&nbsp; --}}
                                    @can('edit_roles')
                                        <a href="{{ route('users.roles.edit', ['role' => $role->id]) }}"
                                           title="@lang('bt.acl_edit_role')"><i
                                                    class="fa fa-pencil-square fa-2x"></i></a>&nbsp;
                                        &nbsp;
                                    @endcan
                                    {{-- <a href="{{ route('roles.destroy', ['role' => $role->id]) }}" title="Remove Role"><i class="fa fa-trash fa-2x"></i></a> --}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <div style="text-align: center;">@lang('bt.no_records_found')</div>
                @endif
            </div>
        </div>
        @include('permissions.index')
    </section>
@stop
