<nav class="main-header navbar navbar-expand navbar-{{ $headClass }} bg-{{ $headBackground }} border-bottom">
    <div class="container-fluid">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-lte-toggle="sidebar-full" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
            </li>
        </ul>
        @push('scripts')
            <script>
                //need to connect up scss bs4 themes...
                //style datatable header and btn-primary like the navbar
                const top_bar = document.querySelector('.bg-{{ $headBackground }}');
                const bg = getComputedStyle(top_bar).backgroundColor;
                let color = '#FFFFFF';
                // override white yellow and light gray color to black
                if (bg === 'rgb(255, 255, 255)' || bg === 'rgb(255, 237, 74)' || bg === 'rgb(242, 244, 245)') {
                    color = '#000000';
                }

                const newStyles = document.createElement('style');
                document.head.append(newStyles);
                newStyles.innerHTML = ".btn-primary, .table.dataTable thead > tr > th {background-color: "
                    + bg + " !important; color: " + color + " !important;}";
            </script>
        @endpush
        <ul class="navbar-nav ms-auto">
            {{--            <li class="nav-item dropdown">--}}
            {{--                <a href="#" class="nav-link" data-bs-toggle="dropdown" title="@lang('bt.utilities')">--}}
            {{--                    <i class="fa fa-toolbox"></i>--}}
            {{--                </a>--}}
            {{--                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">--}}
            {{--                    <li><a class="dropdown-item" href="{{ route('employees.index', ['status' => 'active']) }}"><i--}}
            {{--                                class="fa fa-users"></i> @lang('bt.employees')</a></li>--}}
            {{--                    <li><a class="dropdown-item" href="{{ route('vendors.index', ['status' => 'active']) }}"><i--}}
            {{--                                class="fa fa-warehouse"></i> @lang('bt.vendors')</a></li>--}}
            {{--                    <li><a class="dropdown-item" href="{{ route('products.index', ['status' => 'active']) }}"><i--}}
            {{--                                class="fa fa-shopping-cart"></i> @lang('bt.products')</a></li>--}}
            {{--                    <li><a class="dropdown-item" href="{{ route('categories.index') }}"><i--}}
            {{--                                class="fa fa-list"></i> @lang('bt.categories')</a></li>--}}
            {{--                    <li><a class="dropdown-item" href="{{ route('itemLookups.index') }}"><i--}}
            {{--                                class="fa fa-eye"></i> @lang('bt.item_lookups')</a></li>--}}
            {{--                    <li> <a class="dropdown-item" href="{{ route('mailLog.index') }}"><i--}}
            {{--                                class="fa fa-envelope-square"></i> @lang('bt.mail_log')</a></li>--}}
            {{--                    <li><a class="dropdown-item" href="{{ route('utilities.manage_trash') }}"><i--}}
            {{--                                class="fa fa-trash"></i> @lang('bt.manage_trash')</a></li>--}}
            {{--                    @if(!config('app.demo') && !auth()->user()->client_id && auth()->user()->hasRole('superadmin'))--}}
            {{--                    @hasrole('superadmin')--}}
            {{--                        <li><a class="dropdown-item" href="{{ route('utilities.database') }}"><i--}}
            {{--                                    class="fa fa-save"></i> @lang('bt.manage_database')</a></li>--}}
            {{--                        <li><a class="dropdown-item" href="{{ route('users.manage_acl') }}"><i--}}
            {{--                                    class="fa fa-save"></i> @lang('bt.acl_manage')</a></li>--}}
            {{--                    @endif--}}
            {{--                    @endhasrole--}}
            {{--                </ul>--}}
            {{--            </li>--}}
            {{--            @hasrole('superadmin|admin')--}}
            {{--            <li class="nav-item dropdown">--}}
            {{--                <a href="#" class="nav-link " data-bs-toggle="dropdown" title="@lang('bt.system')">--}}
            {{--                    <i class="fa fa-cog"></i>--}}
            {{--                </a>--}}
            {{--                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">--}}
            {{--                    <a class="dropdown-item" href="{{ route('addons.index') }}">@lang('bt.addons')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('currencies.index') }}">@lang('bt.currencies')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('customFields.index') }}">@lang('bt.custom_fields')</a>--}}
            {{--                    <a class="dropdown-item"--}}
            {{--                       href="{{ route('companyProfiles.index') }}">@lang('bt.company_profiles')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('export.index') }}">@lang('bt.export_data')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('groups.index') }}">@lang('bt.groups')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('import.index') }}">@lang('bt.import_data')</a>--}}
            {{--                    <a class="dropdown-item"--}}
            {{--                       href="{{ route('paymentMethods.index') }}">@lang('bt.payment_methods')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('taxRates.index') }}">@lang('bt.tax_rates')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('users.index') }}">@lang('bt.user_accounts')</a>--}}
            {{--                    <a class="dropdown-item" href="{{ route('settings.index') }}">@lang('bt.system_settings')</a>--}}
            {{--                    @foreach (config('bt.menus.system') as $menu)--}}
            {{--                        @if (view()->exists($menu))--}}
            {{--                            @include($menu)--}}
            {{--                        @endif--}}
            {{--                    @endforeach--}}
            {{--                </div>--}}
            {{--            </li>--}}
            {{--            @endhasrole--}}
            {{--            <li class="nav-item">--}}
            {{--                <a class="nav-link" href="{{ url('documentation', ['Overview']) }}"--}}
            {{--                   title="@lang('bt.documentation')"--}}
            {{--                   aria-haspopup="true" aria-expanded="false" target="_blank">--}}
            {{--                    <i class="fa fa-question-circle"></i>--}}
            {{--                </a>--}}
            {{--            </li>--}}
            <li class="nav-item dropdown user-menu me-3">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ $profileImageUrl }}" class="user-image" alt="User Image"/>
                    <span class="d-none d-md-inline">{{auth()->user()->name}}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                    <!-- User image -->
                    <li class="user-header bg-{{ $headBackground }}">
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
                    <!-- Menu Body -->
                {{--                    <li class="user-body">--}}
                {{--                        <div class="row">--}}
                {{--                            <div class="col-4 text-center">--}}
                {{--                                <a href="#">Followers</a>--}}
                {{--                            </div>--}}
                {{--                            <div class="col-4 text-center">--}}
                {{--                                <a href="#">Sales</a>--}}
                {{--                            </div>--}}
                {{--                            <div class="col-4 text-center">--}}
                {{--                                <a href="#">Friends</a>--}}
                {{--                            </div>--}}
                {{--                        </div>--}}
                {{--                        <!-- /.row -->--}}
                {{--                    </li>--}}
                <!-- Menu Footer-->
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
        </ul>
    </div>
</nav>
{{--</header>--}}
