<script type="text/javascript">
    ready(function () {
        addEvent(document, 'change', "#quote-dashboard-total-setting", (e) => {
            toggleWidgetQuoteDashboardTotalsDateRange(e.target.value);
        });

        function toggleWidgetQuoteDashboardTotalsDateRange(val) {
            if (val === 'custom_date_range') {
                document.getElementById('div-quote-dashboard-totals-date-range').style.display = 'block'
            } else {
                document.getElementById('div-quote-dashboard-totals-date-range').style.display = 'none'
            }
        }

        toggleWidgetQuoteDashboardTotalsDateRange(document.getElementById('quote-dashboard-total-setting').value);
    });
</script>
<div class="mb-3">
    <label>@lang('bt.dashboard_totals_option'): </label>
    {!! Form::select('setting[widgetQuoteSummaryDashboardTotals]', $dashboardTotalOptions, config('bt.widgetQuoteSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'quote-dashboard-total-setting']) !!}
</div>
<div class="row" id="div-quote-dashboard-totals-date-range">
    <div class="col-md-4">
        <label class="form-label fw-bold">@lang('bt.from_date'):</label>
        <x-fp_common
                name="setting[widgetQuoteSummaryDashboardTotalsFromDate]"
                id="quote-dashboard-total-setting-from-date"
                class="form-control"
                value="{{config('bt.widgetQuoteSummaryDashboardTotalsFromDate')}}"></x-fp_common>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold">@lang('bt.to_date'):</label>
        <x-fp_common
                name="setting[widgetQuoteSummaryDashboardTotalsToDate]"
                id="quote-dashboard-total-setting-to-date"
                class="form-control"
                value="{{config('bt.widgetQuoteSummaryDashboardTotalsToDate')}}"></x-fp_common>
    </div>
</div>
