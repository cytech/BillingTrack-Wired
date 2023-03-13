<div id="invoice-dashboard-totals-widget">
    <script type="text/javascript">
        ready(function () {
            addEvent(document, 'click', ".invoice-dashboard-total-change-option", (e) => {
                var option = e.target.dataset.id

                axios.post("{{ route('widgets.dashboard.invoiceSummary.renderPartial') }}", {
                    widgetInvoiceSummaryDashboardTotals: option,
                    widgetInvoiceSummaryDashboardTotalsFromDate: document.getElementById('invoice-dashboard-total-setting-from-date').value,
                    widgetInvoiceSummaryDashboardTotalsToDate: document.getElementById('invoice-dashboard-total-setting-to-date').value
                }).then(function (response) {
                    setInnerHTML(document.getElementById('invoice-dashboard-totals-widget'), response.data)
                })
            });
        });
    </script>
    <div class="card">
        <div class="card-header align-content-center">
            <h5 class="text-bold mb-0 float-start">@lang('bt.invoice_summary')</h5>
            <div class="card-tools float-end">
                <div class="btn-group">
                    <div class="dropdown">
                        <button type="button" class="btn btn-sm" data-bs-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-calendar"></i> {{ $invoiceDashboardTotalOptions[config('bt.widgetInvoiceSummaryDashboardTotals')] }}
                        </button>
                        <div class="dropdown-menu" role="menu">
                            @foreach ($invoiceDashboardTotalOptions as $key => $option)
                                @if ($key != 'custom_date_range')
                                    <a href="#" onclick="return false;"
                                       class="invoice-dashboard-total-change-option  dropdown-item"
                                       data-id="{{ $key }}">{{ $option }}</a>
                                @else
                                    <a href="#" onclick="return false;" data-bs-toggle="modal"
                                       data-bs-target="#invoice-summary-widget-modal"
                                       class="dropdown-item">{{ $option }}</a>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <button class="btn btn-sm"
                        type="button"
                        {{--                   params 3 thru ... mount(,,$modulefullname, $moduleop, $resource_id = null, $module_id = null, $readonly = null)--}}
                        onclick="window.livewire.emit('showModal', 'modals.create-module-modal',  'BT\\Modules\\Invoices\\Models\\Invoice', 'create' )"
                ><i class="fa fa-plus"></i> @lang('bt.create_invoice')
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-purple">
                        <div class="inner">
                            <h4 class="text-bold">{{ $invoicesTotalDraft }}</h4>
                            <p>@lang('bt.draft_invoices')</p>
                        </div>
                        <div class="small-box-faicon">
                            <i class="fa fa-pencil-alt"></i>
                        </div>
                        <a href="{{ route('invoices.index') }}?status=draft" class="small-box-footer">
                            @lang('bt.view_draft_invoices') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-green">
                        <div class="inner">
                            <h4 class="text-bold">{{ $invoicesTotalSent }}</h4>
                            <p>@lang('bt.sent_invoices')</p>
                        </div>
                        <div class="small-box-faicon">
                            <i class="fa fa-share-square"></i>
                        </div>
                        <a class="small-box-footer" href="{{ route('invoices.index') }}?status=sent">
                            @lang('bt.view_sent_invoices') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-red">
                        <div class="inner">
                            <h4 class="text-bold">{{ $invoicesTotalOverdue }}</h4>
                            <p>@lang('bt.overdue_invoices')</p>
                        </div>
                        <div class="small-box-faicon"><i class="fa fa-exclamation"></i></div>
                        <a class="small-box-footer" href="{{ route('invoices.index') }}?status=overdue">
                            @lang('bt.view_overdue_invoices') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12">
                    <div class="small-box text-bg-blue">
                        <div class="inner">
                            <h4 class="text-bold">{{ $invoicesTotalPaid }}</h4>
                            <p>@lang('bt.payments_collected')</p>
                        </div>
                        <div class="small-box-faicon"><i class="fa fa-heart"></i></div>
                        <a class="small-box-footer" href="{{ route('payments.index') }}">
                            @lang('bt.view_payments') <i class="fa fa-arrow-circle-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="invoice-summary-widget-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">@lang('bt.custom_date_range')</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"><span aria-hidden="true"></span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('bt.from_date'):</label>
                        <x-fp_common
                                name="setting_widgetInvoiceSummaryDashboardTotalsFromDate"
                                id="invoice-dashboard-total-setting-from-date"
                                class="form-control"
                                value="{{config('bt.widgetInvoiceSummaryDashboardTotalsFromDate')}}"></x-fp_common>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">@lang('bt.to_date'):</label>
                        <x-fp_common
                                name="setting_widgetInvoiceSummaryDashboardTotalsToDate"
                                id="invoice-dashboard-total-setting-to-date"
                                class="form-control"
                                value="{{config('bt.widgetInvoiceSummaryDashboardTotalsToDate')}}"></x-fp_common>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">@lang('bt.cancel')</button>
                    <button type="button" class="btn btn-primary invoice-dashboard-total-change-option"
                            data-id="custom_date_range" data-bs-dismiss="modal">@lang('bt.save')</button>
                </div>
            </div>
        </div>
    </div>
</div>
