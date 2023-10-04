<?php

namespace BT\Widgets\Dashboard\QuoteSummary\Composers;

use BT\Modules\Documents\Models\DocumentAmount;
use BT\Support\CurrencyFormatter;
use Illuminate\Support\Facades\DB;

class QuoteSummaryWidgetComposer
{
    public function compose($view)
    {
        $view->with('quotesTotalDraft', $this->getQuoteTotalDraft())
            ->with('quotesTotalSent', $this->getQuoteTotalSent())
            ->with('quotesTotalApproved', $this->getQuoteTotalApproved())
            ->with('quotesTotalRejected', $this->getQuoteTotalRejected())
            ->with('quoteDashboardTotalOptions', periods());
    }

    private function getQuoteTotalDraft()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('quote', function ($q)
            {
                $q->draft();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetQuoteSummaryDashboardTotalsFromDate'), config('bt.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getQuoteTotalSent()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('quote', function ($q)
            {
                $q->sent();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetQuoteSummaryDashboardTotalsFromDate'), config('bt.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getQuoteTotalApproved()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('quote', function ($q)
            {
                $q->approved();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetQuoteSummaryDashboardTotalsFromDate'), config('bt.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }

    private function getQuoteTotalRejected()
    {
        return CurrencyFormatter::format(DocumentAmount::join('documents', 'documents.id', '=', 'document_amounts.document_id')
            ->whereHas('quote', function ($q)
            {
                $q->rejected();
                $q->where(function ($qq){$qq->where('invoice_id', 0)->orWhereNull('invoice_id');});
                switch (config('bt.widgetQuoteSummaryDashboardTotals'))
                {
                    case 'year_to_date':
                        $q->yearToDate();
                        break;
                    case 'this_quarter':
                        $q->thisQuarter();
                        break;
                    case 'custom_date_range':
                        $q->dateRange(config('bt.widgetQuoteSummaryDashboardTotalsFromDate'), config('bt.widgetQuoteSummaryDashboardTotalsToDate'));
                        break;
                }
            })->sum(DB::raw('total / exchange_rate')));
    }
}
