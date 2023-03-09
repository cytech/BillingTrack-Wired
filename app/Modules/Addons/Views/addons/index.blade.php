@extends('layouts.master')

@section('content')

    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.addons')</div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>

    <section class="container-fluid">
        @include('layouts._alerts')
        <div class="card card-light">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>@lang('bt.name')</th>
                        <th>@lang('bt.author')</th>
                        <th>@lang('bt.web_address')</th>
                        <th>@lang('bt.status')</th>
                        <th>@lang('bt.options')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($addons as $addon)
                        <tr>
                            <td>{{ $addon->name }}</td>
                            <td>{{ $addon->author_name }}</td>
                            <td>{{ $addon->author_url }}</td>
                            <td>
                                @if ($addon->enabled)
                                    <span class="badge bg-success">@lang('bt.enabled')</span>
                                @else
                                    <span class="badge bg-danger">@lang('bt.disabled')</span>
                                @endif
                            </td>
                            <td>
                                @if ($addon->enabled)
                                    <a href="#" class="btn btn-sm btn-secondary"
                                       onclick="swalConfirm('@lang('bt.uninstall_addon_warning')', '', '{{ route('addons.uninstall', [$addon->id]) }}');">@lang('bt.disable')</a>
                                    @if ($addon->has_pending_migrations)
                                        <a href="{{ route('addons.upgrade', [$addon->id]) }}"
                                           class="btn btn-sm btn-info">@lang('bt.complete_upgrade')</a>
                                    @endif
                                @else
                                    <a href="{{ route('addons.install', [$addon->id]) }}"
                                       class="btn btn-sm btn-secondary">@lang('bt.install')</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@stop
