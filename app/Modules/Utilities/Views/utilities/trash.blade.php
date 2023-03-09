@extends('layouts.master')

@section('content')
    <section class="app-content-header">
        <div class="container-fluid">
            <div class="col-sm-12">
                <div class="fs-3 float-start">@lang('bt.manage_trash')</div>
            </div>
            <div class="clearfix"></div>
        </div>
    </section>
    <section class="content">
        @include('layouts._alerts')
        <div class="card">
                <div class="card m-2">
                    <div class="card-header d-flex p-0">
                        <ul class="nav nav-pills p-2" id="trash-tabs">
                            <li class="nav-item"><a class="nav-link active show" data-bs-toggle="tab"
                                                    href="#tab-clients">@lang('bt.clients')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-quotes">@lang('bt.quotes')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-workorders">@lang('bt.workorders')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-invoices">@lang('bt.invoices')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-recurring_invoices">@lang('bt.recurring_invoices')</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-purchaseorders">@lang('bt.purchaseorders')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-payments">@lang('bt.payments')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-expenses">@lang('bt.expenses')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-projects">@lang('bt.projects')</a></li>
                            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab"
                                                    href="#tab-schedule">@lang('bt.scheduler')</a></li>
                        </ul>
                    </div>
                    <div class="tab-content m-2">
                        <div id="tab-clients" class="tab-pane active">
                            <livewire:data-tables.trash-table :module_type="'Client'"/>
                        </div>
                        <div id="tab-quotes" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Quote'"/>
                        </div>
                        <div id="tab-workorders" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Workorder'"/>
                        </div>
                        <div id="tab-invoices" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Invoice'"/>
                        </div>
                        <div id="tab-recurring_invoices" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'RecurringInvoice'"/>
                        </div>
                        <div id="tab-purchaseorders" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Purchaseorder'"/>
                        </div>
                        <div id="tab-payments" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Payment'"/>
                        </div>
                        <div id="tab-expenses" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Expense'"/>
                        </div>
                        <div id="tab-projects" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'TimeTrackingProject'"/>
                        </div>
                        <div id="tab-schedule" class="tab-pane">
                            <livewire:data-tables.trash-table :module_type="'Schedule'"/>
                        </div>
                    </div>
                </div>
        </div>
    </section>
@stop
@push('scripts')
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', "#trash-tabs a", (e) => {
                const tabId = e.target.getAttribute('href').slice(1)
                axios.post("{{ route('utilities.saveTab') }}", {trashTabId: tabId});
            })
            let ttid = '{{ session('trashTabId') }}' ? '{{ session('trashTabId') }}' : 'tab-clients'
            var triggerEl = new bootstrap.Tab(document.querySelector('#trash-tabs a[href="#' + ttid + '"]'))
            triggerEl.show() // Select tab by name
        });
    </script>
@endpush

