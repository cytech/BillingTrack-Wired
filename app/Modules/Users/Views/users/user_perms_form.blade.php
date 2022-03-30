<div class="form-group">
    <div class="card">
        <div class="card-header h3">Additional User @lang('bt.acl_permissions')
        </div>
        <div class="card-body">
            @foreach($permissions->groupBy('group') as $group)
                <div class="h4">{{$group->first()->group}}</div>
                <table id="permissions"
                       class="table table-striped table-hover table-responsive-sm table-sm compact mb-3">
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
                            <td><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                       @if(isset($user) && $user->hasDirectPermission($permission->name)) checked @endif>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>
</div>
