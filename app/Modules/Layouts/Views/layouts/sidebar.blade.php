<aside class="main-sidebar sidebar-mini sidebar-{{ $headClass }}">
    <div class="brand-container bg-{{ $headBackground }} ">
        <div class="brand-link ">
            <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo"
                 class="brand-image img-circle elevation-3 img-sm pe-1"
                 style="opacity: .8">
            <span class="brand-text fw-light">{{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
        </div>
        <a class="pushmenu mx-1" data-enable-remember="true" data-lte-toggle="sidebar-mini" href="javascript:;"
           role="button"><i
                    class="fas fa-angle-double-left"></i></a>
    </div>
    <div class="sidebar bg-{{ $sidebarBackground }}">
        {{--        @if (config('bt.displayProfileImage'))--}}
        {{--            <div class="user-panel">--}}
        {{--                <div class="float-start image">--}}
        {{--                    <img src="{{ $profileImageUrl }}" alt="User Image"/>--}}
        {{--                </div>--}}
        {{--                <div class="float-start info">--}}
        {{--                    <p>{{ $userName }}</p>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        @endif--}}

        {{--        @if (isset($displaySearch) and $displaySearch == true)--}}
        {{--            <form action="{{ request()->fullUrl() }}" method="get" class="sidebar-form">--}}
        {{--                <input type="hidden" name="status" value="{{ request('status') }}"/>--}}
        {{--                <div class="input-group">--}}
        {{--                    <input type="text" name="search" class="form-control"--}}
        {{--                           placeholder="@lang('bt.search')..."/>--}}
        {{--                <button type="submit" id="search-btn" class="btn btn-flat input-group-text "><i class="far fa-search"></i></button>--}}
        {{--                </div>--}}
        {{--            </form>--}}
        {{--        @endif--}}
        <nav class="mt-2">
            <ul class="nav nav-pills-{{ $sidebarClass }} nav-sidebar flex-column" data-lte-toggle="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('dashboard.index') }}">
                        <i class="nav-icon fas fa-tachometer-alt fa-fw"></i>
                        <p>@lang('bt.dashboard')</p>
                    </a>
                </li>
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('quote'))
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('quotes.index', ['status' => config('bt.quoteStatusFilter')]) }}">
                            <i class="nav-icon far fa-file-alt fa-fw"></i>
                            <p>@lang('bt.quotes')</p>
                        </a>
                    </li>
                @endif
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('workorder'))
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('workorders.index', ['status' => config('bt.workorderStatusFilter')]) }}">
                            <i class="nav-icon far fa-file-alt fa-fw"></i>
                            <p>@lang('bt.workorders')</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link"
                       href="{{ route('invoices.index', ['status' => config('bt.invoiceStatusFilter')]) }}">
                        <i class="nav-icon fas fa-file-alt fa-fw"></i>
                        <p>@lang('bt.invoices')</p>
                    </a>
                </li>
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('recurring_invoice'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('recurringInvoices.index') }}">
                            <i class="nav-icon fas fa-sync-alt fa-fw"></i>
                            <p>@lang('bt.recurring_invoices')</p>
                        </a>
                    </li>
                @endif
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('purchaseorder'))
                    <li class="nav-item">
                        <a class="nav-link"
                           href="{{ route('purchaseorders.index', ['status' => config('bt.invoiceStatusFilter')]) }}">
                            <i class="nav-icon fas fa-file-alt fa-fw"></i>
                            <p>@lang('bt.purchaseorders')</p>
                        </a>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('payments.index') }}">
                        <i class="nav-icon fas fa-credit-card fa-fw"></i>
                        <p>@lang('bt.payments')</p>
                    </a>
                </li>
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('expense'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('expenses.index') }}">
                            <i class="nav-icon fas fa-dollar-sign fa-fw"></i>
                            <p>@lang('bt.expenses')</p>
                        </a>
                    </li>
                @endif
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('time_tracking'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('timeTracking.projects.index', ['status' => 1]) }}">
                            <i class="nav-icon far fa-clock fa-fw"></i>
                            <p>@lang('bt.time_tracking')</p>
                        </a>
                    </li>
                @endif
                @if(\BT\Modules\Settings\Models\Setting::isModuleEnabled('scheduler'))
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="nav-icon far fa-calendar fa-fw"></i>
                            <p>@lang('bt.scheduler')</p>
                            <i class="end fas fa-angle-left "></i>
                        </a>
                        <ul class="nav nav-treeview ps-3">
                            <li class="nav-item"><a class="nav-link" href="{{ route('scheduler.index') }}"><i
                                            class="nav-icon fas fa-tachometer-alt fa-fw"></i>
                                    <p>@lang('bt.dashboard')</p></a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('scheduler.fullcalendar') }}"><i
                                            class="nav-icon far fa-calendar-alt fa-fw"></i>
                                    <p>@lang('bt.calendar')</p></a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('scheduler.showschedule') }}"><i
                                            class="nav-icon far fa-clock fa-fw"></i>
                                    <p>@lang('bt.schedule')</p></a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="{{ route('scheduler.tableevent') }}"><i
                                            class="nav-icon fas fa-table fa-fw"></i>
                                    <p>@lang('bt.table_event')</p></a>
                            </li>
                            <li class="nav-item"><a class="nav-link"
                                                    href="{{ route('scheduler.tablerecurringevent') }}"><i
                                            class="nav-icon fas fa-sync-alt fa-fw"></i>
                                    <p>@lang('bt.recurring_event')</p></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><i class="nav-icon fas fa-cogs fa-fw"></i>
                                    <p>@lang('bt.utilities')</p><i
                                            class="end fas fa-angle-left"></i></a>
                                <ul class="nav nav-treeview ps-3">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('scheduler.categories.index') }}"><i
                                                    class="nav-icon fas fa-thumbtack fa-fw "></i>
                                            <p>@lang('bt.categories')</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('scheduler.checkschedule') }}"><i
                                                    class="nav-icon fas fa-check-double fa-fw "></i>
                                            <p>@lang('bt.orphan_check')</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>@lang('bt.reports')</p>
                        <i class="end fa fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview ps-3">
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.clientStatement') }}"> @lang('bt.client_statement')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.expenseList') }}"> @lang('bt.expense_list')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.itemSales') }}"> @lang('bt.item_sales')</a></li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.paymentsCollected') }}"> @lang('bt.payments_collected')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.profitLoss') }}"> @lang('bt.profit_and_loss')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.revenueByClient') }}"> @lang('bt.revenue_by_client')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.taxSummary') }}"> @lang('bt.tax_summary')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.timeTracking') }}"> @lang('bt.time_tracking')</a>
                        </li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('reports.timesheet') }}">@lang('bt.timesheet')</a></li>
                        @foreach (config('bt.menus.reports') as $report)
                            @if (view()->exists($report))
                                @include($report)
                            @endif
                        @endforeach
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="nav-icon fas fa-diagram-project"></i>
                        <p>@lang('bt.resources')</p>
                        <i class="end fa fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview ps-3">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('clients.index', ['status' => 'active']) }}">
                                <i class="nav-icon fas fa-users fa-fw"></i>
                                <p>@lang('bt.clients')</p>
                            </a>
                        </li>
                        @hasrole('superadmin|admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('employees.index', ['status' => 'active']) }}">
                                <i class="nav-icon fa-solid fa-users-gear fa-fw"></i>
                                <p>@lang('bt.employees')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('vendors.index', ['status' => 'active']) }}">
                                <i class="nav-icon fa-solid fa-warehouse fa-fw"></i>
                                <p>@lang('bt.vendors')</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('products.index', ['status' => 'active']) }}">
                                <i class="nav-icon fa-solid fa-cart-shopping fa-fw"></i>
                                <p>@lang('bt.products')</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="nav-icon fas fa-toolbox fa-fw"></i>
                        <p>@lang('bt.utilities')</p>
                        <i class="end fa fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview ps-3">
                        <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}"><i
                                        class="fas fa-list fa-fw"></i>
                                <p>@lang('bt.categories')</p></a></li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('companyProfiles.index') }}"><i
                                        class="fas fa-building fa-fw"></i>
                                <p>@lang('bt.company_profiles')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('currencies.index') }}"><i
                                        class="fas fa-coins fa-fw"></i>
                                <p>@lang('bt.currencies')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('groups.index') }}"><i
                                        class="fas fa-object-group fa-fw"></i>
                                <p>@lang('bt.groups')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('itemLookups.index') }}"><i
                                        class="fas fa-eye fa-fw"></i>
                                <p>@lang('bt.item_lookups')</p></a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('mailLog.index') }}"><i
                                        class="fas fa-envelope-square fa-fw"></i>
                                <p>@lang('bt.mail_log')</p></a></li>
                        <li class="nav-item"><a class="nav-link"
                                                href="{{ route('paymentMethods.index') }}"><i
                                        class="fas fa-credit-card fa-fw"></i>
                                <p>@lang('bt.payment_methods')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('taxRates.index') }}"><i
                                        class="fas fa-percent fa-fw"></i>
                                <p>@lang('bt.tax_rates')</p></a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">
                        <i class="nav-icon fas fa-gears fa-fw"></i>
                        <p>@lang('bt.admin')</p>
                        <i class="end fa fa-angle-left"></i>
                    </a>
                    <ul class="nav nav-treeview ps-3">
                        <li class="nav-item"><a class="nav-link" href="{{ route('customFields.index') }}"><i
                                        class="fas fa-table fa-fw"></i>
                                <p></p>@lang('bt.custom_fields')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.index') }}"><i
                                        class="fas fa-users fa-fw"></i>
                                <p>@lang('bt.user_accounts')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('settings.index') }}"><i
                                        class="fas fa-gear fa-fw"></i>
                                <p>@lang('bt.system_settings')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('utilities.manage_trash') }}"><i
                                        class="fas fa-trash fa-fw"></i>
                                <p>@lang('bt.manage_trash')</p></a>
                        </li>
                        @hasrole('superadmin')
                        <li class="nav-item"><a class="nav-link" href="{{ route('addons.index') }}"><i
                                        class="fas fa-puzzle-piece fa-fw"></i>
                                <p>@lang('bt.addons')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('export.index') }}"><i
                                        class="fas fa-download fa-fw"></i>
                                <p>@lang('bt.export_data')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('import.index') }}"><i
                                        class="fas fa-upload fa-fw"></i>
                                <p>@lang('bt.import_data')</p></a>
                        </li>
                        @if (!config('app.demo'))
                        <li class="nav-item"><a class="nav-link" href="{{ route('utilities.database') }}"><i
                                        class="fas fa-database fa-fw"></i>
                                <p>@lang('bt.manage_database')</p></a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('users.manage_acl') }}"><i
                                        class="fas fa-user-lock fa-fw"></i>
                                <p>@lang('bt.acl_manage')</p></a>
                        </li>
                        @endif
                        @endhasrole
                    </ul>
                </li>
                @endhasrole
                @foreach (config('bt.menus.navigation') as $menu)
                    @if (view()->exists($menu))
                        @include($menu)
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>
</aside>
