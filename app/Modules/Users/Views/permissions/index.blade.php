<div class="card">
    <div class="card-header h3">@lang('bt.acl_permissions')
        @can('create_permissions')
            <div style="float:right;padding-right:20px;"><a href="{{ route('users.permissions.create') }}"
                                                            title="@lang('bt.acl_add_permission')"
                                                            class="btn btn-primary "><i
                            class="fa fa-plus"></i> @lang('bt.acl_add_permission')</a>
            </div>
        @endcan
    </div>
    <div class="card-body">
        @if (count($permissions))
            @foreach($permissions->groupBy('group') as $group)
                <div class="h4">{{$group->first()->group}}</div>
                <table id="permissions" class="table table-striped table-hover table-responsive-sm table-sm compact mb-3">
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
                    @foreach($group as $permission)
                        <tr>
                            <td>{{ $permission->id }}</td>
                            <td>{{ $permission->name }}</td>
                            <td>{{ $permission->description }}</td>
                            <td>{{ $permission->guard_name }}</td>
                            <td>{{ $permission->created_at->toFormattedDateString() }}</td>
                            <td>{{ $permission->updated_at->toFormattedDateString() }}</td>
                            <td>
                                {{-- <a href="{{ route('permissions.show', ['permission' => $permission->id]) }}" title="View Permission Details"><i class="fa fa-info-circle fa-2x"></i></a>&nbsp;&nbsp; --}}
                                @can('edit_permissions')
                                    <a href="{{ route('users.permissions.edit', ['permission' => $permission->id]) }}"
                                       title="@lang('bt.acl_edit_permission')"><i class="fa fa-pencil-square fa-2x"></i></a>
                                    &nbsp;&nbsp;
                                @endcan
                                {{-- <a href="{{ route('permissions.destroy', ['permission' => $permission->id]) }}" title="Remove Permission"><i class="fa fa-trash fa-2x"></i></a> --}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endforeach
        @else
            <div style="text-align: center;">@lang('bt.no_records_found')</div>
        @endif
    </div>
</div>
