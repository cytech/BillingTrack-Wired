<script type="text/javascript">
    ready(function () {
        addEvent(document, 'change', "#workorder-dashboard-total-setting", (e) => {
            toggleWidgetWorkorderDashboardTotalsDateRange(e.target.value);
        });

        function toggleWidgetWorkorderDashboardTotalsDateRange(val) {
            if (val === 'custom_date_range') {
                document.getElementById('div-workorder-dashboard-totals-date-range').style.display = 'block'
            } else {
                document.getElementById('div-workorder-dashboard-totals-date-range').style.display = 'none'
            }
        }

        toggleWidgetWorkorderDashboardTotalsDateRange(document.getElementById('workorder-dashboard-total-setting').value);
    });
</script>
<div class="mb-3">
    <label>@lang('bt.dashboard_totals_option'): </label>
    {!! Form::select('setting[widgetWorkorderSummaryDashboardTotals]', $dashboardTotalOptions, config('bt.widgetWorkorderSummaryDashboardTotals'), ['class' => 'form-control', 'id' => 'workorder-dashboard-total-setting']) !!}
</div>
<div class="row" id="div-workorder-dashboard-totals-date-range">
    <div class="col-md-4">
        <label class="form-label fw-bold">@lang('bt.from_date'):</label>
        <x-fp_common
                name="setting[widgetWorkorderSummaryDashboardTotalsFromDate]"
                id="workorder-dashboard-total-setting-from-date"
                class="form-control"
                value="{{config('bt.widgetWorkorderSummaryDashboardTotalsFromDate')}}"></x-fp_common>
    </div>
    <div class="col-md-4">
        <label class="form-label fw-bold">@lang('bt.to_date'):</label>
        <x-fp_common
                name="setting[widgetWorkorderSummaryDashboardTotalsToDate]"
                id="workorder-dashboard-total-setting-to-date"
                class="form-control"
                value="{{config('bt.widgetWorkorderSummaryDashboardTotalsToDate')}}"></x-fp_common>
    </div>
</div>
