<aside class="main-sidebar sidebar-mini sidebar-{{ $headClass }}">
    <div class="brand-container bg-{{ $headBackground }} ">
        <div class="brand-link ">
            <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo"
                 class="brand-image img-circle elevation-3 img-sm pe-1"
                 style="opacity: .8">
            <span class="brand-text fw-light">{{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
        </div>
    </div>
    <div class="sidebar bg-{{ $sidebarBackground }}">
        <nav class="mt-2">
            <ul class="nav nav-pills-{{ $sidebarClass }} nav-sidebar flex-column" data-lte-toggle="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientCenter.dashboard') }}">
                        <i class="fas fa-tachometer-alt fa-fw"></i>
                        <p>@lang('bt.dashboard')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientCenter.quotes') }}">
                        <i class="far fa-file-alt fa-fw"></i>
                        <p>@lang('bt.quotes')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientCenter.workorders') }}">
                        <i class="fas fa-file-alt fa-fw"></i>
                        <p>@lang('bt.workorders')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientCenter.invoices') }}">
                        <i class="far fa-file-alt fa-fw"></i>
                        <p>@lang('bt.invoices')</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('clientCenter.payments') }}">
                        <i class="fas fa-credit-card fa-fw"></i>
                        <p>@lang('bt.payments')</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
