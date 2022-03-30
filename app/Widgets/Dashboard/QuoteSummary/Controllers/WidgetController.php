<?php

namespace BT\Widgets\Dashboard\QuoteSummary\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Settings\Models\Setting;

class WidgetController extends Controller
{
    public function renderPartial()
    {
        Setting::saveByKey('widgetQuoteSummaryDashboardTotals', request('widgetQuoteSummaryDashboardTotals'));

        if (request()->has('widgetQuoteSummaryDashboardTotalsFromDate'))
        {
            Setting::saveByKey('widgetQuoteSummaryDashboardTotalsFromDate', request('widgetQuoteSummaryDashboardTotalsFromDate'));
        }

        if (request()->has('widgetQuoteSummaryDashboardTotalsToDate'))
        {
            Setting::saveByKey('widgetQuoteSummaryDashboardTotalsToDate', request('widgetQuoteSummaryDashboardTotalsToDate'));
        }

        Setting::setAll();

        return view('QuoteSummaryWidget');
    }
}
