<script type="text/javascript">
    ready(function () {
        addEvent(document, 'change', "#invoice-dashboard-total-setting", (e) => {
            toggleWidgetInvoiceDashboardTotalsDateRange(e.target.value);
        });

        function toggleWidgetInvoiceDashboardTotalsDateRange(val) {
            if (val === 'custom_date_range') {
                document.getElementById('div-invoice-dashboard-totals-date-range').style.display = 'block'
            } else {
                document.getElementById('div-invoice-dashboard-totals-date-range').style.display = 'none'
            }
        }

        toggleWidgetInvoiceDashboardTotalsDateRange(document.getElementById('invoice-dashboard-total-setting').value);
    });
</script>
<div class="mb-3">
    <label>@lang('bt.dashboard_totals_option'): </label>
    {!! Form::select('setting[widgetInvoiceSummaryDashboardTotals]', $dashboardTotalOptions, config('bt.widgetInvoiceSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'invoice-dashboard-total-setting']) !!}
</div>
<div class="row" id="div-invoice-dashboard-totals-date-range">
    <div class="col-md-4">
        <label class="form-label fw-bold">@lang('bt.from_date'):</label>
        <x-fp_common
                name="setting[widgetInvoiceSummaryDashboardTotalsFromDate]"
                id="invoice-dashboard-total-setting-from-date"
                class="form-control"
                value="{{config('bt.widgetInvoiceSummaryDashboardTotalsFromDate')}}"></x-fp_common>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold">@lang('bt.to_date'):</label>
        <x-fp_common
                name="setting[widgetInvoiceSummaryDashboardTotalsToDate]"
                id="invoice-dashboard-total-setting-To-date"
                class="form-control"
                value="{{config('bt.widgetInvoiceSummaryDashboardTotalsToDate')}}"></x-fp_common>
    </div>
</div>
