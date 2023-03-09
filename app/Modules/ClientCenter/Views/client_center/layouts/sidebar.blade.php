<aside class="app-sidebar">
    <div class="sidebar-brand bg-body">
        <div class="brand-link ">
            <img src="/img/billingtrack_logo.svg" alt="BillingTrack Logo"
                 class="brand-image img-circle elevation-3 img-sm pe-1">
            <span class="brand-text">{{ config('bt.headerTitleText', config('app.name','BillingTrack')) }}</span>
        </div>
    </div>
    <div class="sidebar-wrapper">
        <nav class="mt-2">
            <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="menu" data-accordion="false">
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
