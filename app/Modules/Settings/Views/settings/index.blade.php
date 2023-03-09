@extends('layouts.master')

@section('javaScript')
    @parent
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', "#btn-submit", (e) => {
                document.getElementById('form-settings').submit()
            })

            addEvent(document, 'click', "#btn-recalculate-invoices", (e) => {
                const btn = e.target
                btn.innerHTML = 'loading...'
                axios.post("{{ route('invoices.recalculate') }}").then(function (response) {
                    notify(response.data.message, 'info');
                }).catch(function (error) {
                    notify('@lang('bt.error'): ' + error.response.data.errors, 'error');
                }).then(function () {
                    btn.innerHTML = 'Recalculate'
                });
            })

            addEvent(document, 'click', "#btn-recalculate-workorders", (e) => {
                const btn = e.target
                btn.innerHTML = 'loading...'
                axios.post("{{ route('workorders.recalculate') }}").then(function (response) {
                    notify(response.data.message, 'info');
                }).catch(function (error) {
                    notify('@lang('bt.error'): ' + error.response.data.errors, 'error');
                }).then(function () {
                    btn.innerHTML = 'Recalculate'
                });
            });

            addEvent(document, 'click', "#btn-recalculate-quotes", (e) => {
                const btn = e.target
                btn.innerHTML = 'loading...'
                axios.post("{{ route('quotes.recalculate') }}").then(function (response) {
                    notify(response.data.message, 'info');
                }).catch(function (error) {
                    notify('@lang('bt.error'): ' + error.response.data.errors, 'error');
                }).then(function () {
                    btn.innerHTML = 'Recalculate'
                });
            });

            addEvent(document, 'click', "#btn-recalculate-purchaseorders", (e) => {
                const btn = e.target
                btn.innerHTML = 'loading...'
                axios.post("{{ route('purchaseorders.recalculate') }}").then(function (response) {
                    notify(response.data.message, 'info');
                }).catch(function (error) {
                    notify('@lang('bt.error'): ' + error.response.data.errors, 'error');
                }).then(function () {
                    btn.innerHTML = 'Recalculate'
                });
            });

            addEvent(document, 'click', "#setting-tabs a", (e) => {
                const tabId = e.target.getAttribute('href').slice(1)
                axios.post("{{ route('settings.saveTab') }}", {settingTabId: tabId});
            })

            let stid = '{{ session('settingTabId') }}' ? '{{ session('settingTabId') }}' : 'tab-general'
            var triggerEl = new bootstrap.Tab(document.querySelector('#setting-tabs a[href="#' + stid + '"]'))
            triggerEl.show() // Select tab by name
        });
    </script>
@stop

@section('content')
    <section class="app-content-header">
        {!! Form::open(['route' => 'settings.update', 'files' => true, 'id' => 'form-settings']) !!}
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.system_settings')</div>
                <div class="btn-group float-end">
                    <div class="btn-group">
                        <a class="btn btn-warning " href={!! route('dashboard.index')  !!}><i
                                    class="fa fa-ban"></i> @lang('bt.cancel')</a>
                        @if (!config('app.demo'))
                            <button type="submit" class="btn btn-primary "><i
                                        class="fa fa-save"></i> @lang('bt.save') </button>
                        @else
                            <p class="btn btn-primary  " disabled><i
                                        class="fa fa-save"></i> Save disabled in demo. </p>
                        @endif
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card">
                <div class="card m-2">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills" id="setting-tabs">
                            <li class="nav-item"><a class="nav-link active show" data-bs-toggle="tab"
                                                    href="#tab-general">@lang('bt.general')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-dashboard">@lang('bt.dashboard')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-quotes">@lang('bt.quotes')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-workorders">@lang('bt.workorders')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-invoices">@lang('bt.invoices')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-purchaseorders">@lang('bt.purchaseorders')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-taxes">@lang('bt.taxes')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-email">@lang('bt.email')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-pdf">@lang('bt.pdf')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-online-payments">@lang('bt.online_payments')</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-scheduler">@lang('bt.scheduler')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-system">@lang('bt.system')</a></li>
                        </ul>
                    </div>
                    <div class="tab-content m-2">
                        <div id="tab-general" class="tab-pane active">
                            @include('settings._general')
                        </div>
                        <div id="tab-dashboard" class="tab-pane">
                            @include('settings._dashboard')
                        </div>
                        <div id="tab-invoices" class="tab-pane">
                            @include('settings._invoices')
                        </div>
                        <div id="tab-purchaseorders" class="tab-pane">
                            @include('settings._purchaseorders')
                        </div>
                        <div id="tab-workorders" class="tab-pane">
                            @include('settings._workorders')
                        </div>
                        <div id="tab-quotes" class="tab-pane">
                            @include('settings._quotes')
                        </div>
                        <div id="tab-taxes" class="tab-pane">
                            @include('settings._taxes')
                        </div>
                        <div id="tab-email" class="tab-pane">
                            @include('settings._email')
                        </div>
                        <div id="tab-pdf" class="tab-pane">
                            @include('settings._pdf')
                        </div>
                        <div id="tab-online-payments" class="tab-pane">
                            @include('settings._online_payments')
                        </div>
                        <div id="tab-scheduler" class="tab-pane">
                            @include('settings._scheduler')
                        </div>
                        <div id="tab-system" class="tab-pane">
                            @include('settings._system')
                        </div>
                    </div>
                </div>
        </div>
        {!! Form::close() !!}
    </section>
@stop
