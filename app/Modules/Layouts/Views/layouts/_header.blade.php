<nav class="app-header navbar navbar-expand bg-body border-bottom">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"><i
                            class="fa-solid fa-bars"></i></a>
            </li>
        </ul>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown user-menu me-3">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ $profileImageUrl }}" class="user-image" alt="User Image"/>
                    <span class="d-none d-md-inline">{{auth()->user()->name}}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User image -->
                    <li class="user-header text-bg-light">
                        <img src="{{ $profileImageUrl }}" class="user-image" alt="User Image"/>
                        <p>
                            @if(!auth()->user()->client_id)
                                {{auth()->user()->name}} - {{auth()->user()->getRoleNames()->implode(',')}}
                            @else
                                {{auth()->user()->name}}
                            @endif
                            <small>User since {{auth()->user()->created_at->format('M, Y')}}</small>
                        </p>
                    </li>
                    <li class="user-footer">
                        {{--                        <a href="#" class="btn btn-default btn-flat">Profile</a>--}}
                        <div class="d-flex justify-content-between">
                            @if (!config('app.demo'))
                                <a class="btn btn-default btn-flat"
                                   href="{{ route('users.edit', [auth()->user()->id, auth()->user()->user_type]) }}"><i
                                            class="fa fa-edit"></i> @lang('bt.edit') @lang('Profile') </a>
                            @endif
                            <a class="btn btn-default btn-flat" href="{{ route('session.logout') }}"
                               title="@lang('bt.sign_out')"
                               aria-haspopup="true" aria-expanded="false">@lang('bt.sign_out')</a>
                        </div>
                        <hr>
                        <div class="row">
                            <a class="btn btn-default btn-flat" href="{{ url('documentation', ['Overview']) }}"
                               title="@lang('bt.documentation')"
                               aria-haspopup="true" aria-expanded="false" target="_blank">
                                BillingTrack @lang('bt.documentation')
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
{{--            @include('layouts._themer')--}}
        </ul>
    </div>
</nav>

