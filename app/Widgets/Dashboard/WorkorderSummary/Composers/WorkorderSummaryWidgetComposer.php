<?php

namespace BT\Widgets\Dashboard\WorkorderSummary\Composers;

use BT\Modules\Documents\Models\DocumentAmount;
use BT\Support\CurrencyFormatter;
use Illuminate\Support\Facades\DB;

class WorkorderSummaryWidgetComposer
{
    public function compose($view)
    {
        $view->with('workordersTotalDraft', $this->getWorkorderTotalDraft())
            ->with('workordersTotalSent', $this->getWorkorderTotalSent())
            ->with('workordersTotalApproved', $this->getWorkorderTotalApproved())
            ->with('workordersTotalRejected', $this->getWorkorderTotalRejected())
            ->with('workorderDashboardTotalOptions', periods());
    }

    private function getWorkorderTotalDraft()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('workorder', function ($q) {
                $q->draft();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetWorkorderSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetWorkorderSummaryDashboardTotalsFromDate'), config('bt.widgetWorkorderSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getWorkorderTotalSent()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('workorder', function ($q) {
                $q->sent();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetWorkorderSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetWorkorderSummaryDashboardTotalsFromDate'), config('bt.widgetWorkorderSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getWorkorderTotalApproved()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('workorder', function ($q) {
                $q->approved();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetWorkorderSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetWorkorderSummaryDashboardTotalsFromDate'), config('bt.widgetWorkorderSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getWorkorderTotalRejected()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('workorder', function ($q) {
                $q->rejected();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetWorkorderSummaryDashboardTotals')) {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetWorkorderSummaryDashboardTotalsFromDate'), config('bt.widgetWorkorderSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }
}
