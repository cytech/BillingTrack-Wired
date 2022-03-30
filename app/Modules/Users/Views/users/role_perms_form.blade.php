<script type="text/javascript">
    function selects() {
        var ele = document.getElementsByName('permissions[]');
        for (var i = 0; i < ele.length; i++) {
            if (ele[i].type === 'checkbox')
                ele[i].checked = true;
        }
    }

    function deSelect() {
        var ele = document.getElementsByName('permissions[]');
        for (var i = 0; i < ele.length; i++) {
            if (ele[i].type === 'checkbox')
                ele[i].checked = false;

        }
    }
</script>
<div class="form-group">
    <div class="card border">
        <div class="card-title border-bottom ps-3 fs-4 bg-secondary">
            @lang('bt.acl_perm_grant_role')
        </div>
        <input type="button" onclick='selects()' value="@lang('bt.select_all')"/>
        <input type="button" onclick='deSelect()' value="@lang('bt.deselect_all')"/>
        <div class="card-body">
            @foreach($permissions as $key => $subgroup)
                <h1 class="h4">{{$key}}</h1>
                @if($key <> 'Reports')
                    <table id="permissions{{$loop->index}}"
                           class="table table-striped table-hover table-responsive-sm table-sm compact mb-3">
                        <thead>
                        <tr>
                            <th style="width:20%;">@lang('bt.name')</th>
                            <th style="width:20%;">@lang('bt.view')</th>
                            <th style="width:20%;">@lang('bt.create')</th>
                            <th style="width:20%;">@lang('bt.edit')</th>
                            <th style="width:20%;">@lang('bt.delete')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subgroup as $subkey => $group)
                            <tr>
                                @foreach ($group as $permission)
                                    <?php
                                    $perm_found = null;
                                    if (isset($role)) {
                                        $perm_found = $role->hasPermissionTo($permission->name);
                                    }
                                    if (isset($user)) {
                                        $perm_found = $user->hasDirectPermission($permission->name);
                                    }
                                    ?>

                                    @if($loop->index % 4 == 0)
                                        <td>{{ $subkey }}</td>
                                    @endif
                                    @if(\Illuminate\Support\Str::before($permission->name, '_') == 'view')
                                        <td><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   @if($perm_found) checked @endif></td>
                                    @elseif(\Illuminate\Support\Str::before($permission->name, '_') == 'create')
                                        <td><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   @if($perm_found) checked @endif></td>
                                    @elseif(\Illuminate\Support\Str::before($permission->name, '_') == 'edit')
                                        <td><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   @if($perm_found) checked @endif></td>
                                    @else
                                        <td><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   @if($perm_found) checked @endif></td>
                                    @endif
                                    @continue
                                @endforeach
                            </tr>
                            @continue
                        @endforeach
                        </tbody>
                    </table>
                @else
                    <table id="permissions{{$loop->index}}"
                           class="table table-striped table-hover table-responsive-sm table-sm compact mb-3">
                        <thead>
                        <tr>
                            <th style="width:20%;">@lang('bt.name')</th>
                            <th style="width:20%;">@lang('bt.view')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($subgroup as $subkey => $group)
                            @foreach ($group as $permission)
                                <tr>
                                    <?php
                                    $perm_found = null;
                                    if (isset($role)) {
                                        $perm_found = $role->hasPermissionTo($permission->name);
                                    }
                                    if (isset($user)) {
                                        $perm_found = $user->hasDirectPermission($permission->name);
                                    }
                                    ?>
                                    <td>{{ \Illuminate\Support\Str::after($permission->name, '_') }}</td>
                                    @if(\Illuminate\Support\Str::before($permission->name, '_') == 'view')
                                        <td><input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                   @if($perm_found) checked @endif></td>
                                    @endif
                                    @continue
                                    @endforeach
                                </tr>
                                @continue
                            @endforeach
                        </tbody>
                    </table>
                @endif
            @endforeach
        </div>
    </div>
</div>
