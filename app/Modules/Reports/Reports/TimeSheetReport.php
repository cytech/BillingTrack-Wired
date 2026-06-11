<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Reports\Reports;

use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Documents\Models\Invoice;
use BT\Support\DateFormatter;
use BT\Support\Statuses\DocumentStatuses;
use DB;

class TimeSheetReport
{
    public function getResults($fromDate, $toDate, $companyProfileId = null, $report_type = null): array
    {
        $results = [
            'from_date' => '',
            'to_date' => '',
            'total_records',
            'total_hours' => '',
            'records' => [],
        ];

        $invoices = DocumentItem::whereHas('invoice', function ($query) use ($fromDate, $toDate) {
            $query->whereBetween('document_date', [$fromDate, $toDate])
                ->where('document_status_id', '<>', DocumentStatuses::getStatusId('canceled'))
                ->where('document_status_id', '<>', DocumentStatuses::getStatusId('draft'));
        })
            ->withAggregate('invoice', 'document_date')
            ->withAggregate('employee', 'full_name')
            ->where('resource_table', 'employees')
            ->orderBy('employee_full_name')
            ->orderBy('invoice_document_date', 'DESC');

        if ($companyProfileId) {
            $companyProfile = CompanyProfile::where('id', $companyProfileId)->first();
            $results['companyProfile_company'] = $companyProfile->company;

            $invoices->whereRelation('invoice', 'company_profile_id', '=', $companyProfileId);
        } else {
            $results['companyProfile_company'] = __('bt.all_billing');
        }

        $invoices = $invoices->get();

        $results['from_date'] = DateFormatter::format($fromDate);
        $results['to_date'] = DateFormatter::format($toDate);
        $results['total_records'] = count($invoices);
        $results['report_type'] = $report_type;

        if (! count($invoices)) {

            return $results;
        }

        $totalhours = $invoices->sum('quantity');

        if ($report_type == 'condensed') {
            $groups = $invoices->groupBy('resource_id');
            $groupwithcount = $groups->map(function ($group) {
                return [
                    'resource_id' => $group->first()->resource_id,
                    'quantity' => $group->sum('quantity'),
                    'name' => $group->first()->name,
                    'full_name' => $group->first()->employee->full_name,
                    'employee_number' => $group->first()->employee->number,
                ];
            });

            foreach ($groupwithcount as $invoice) {
                $results['records'][] = [
                    'item_name' => $invoice['name'],
                    'item_qty' => $invoice['quantity'],
                    'full_name' => $invoice['full_name'],
                    'employee_number' => $invoice['employee_number'],
                ];
            }
        } else {
            foreach ($invoices as $invoice) {
                $results['records'][] = [
                    'number' => $invoice->invoice->number,
                    'client_name' => $invoice->invoice->client->name,
                    'formatted_document_date' => $invoice->invoice->document_date,
                    'item_name' => $invoice->name,
                    'item_qty' => $invoice->quantity,
                    'full_name' => $invoice->employee->full_name,
                    'employee_number' => $invoice->employee->number,
                ];

            }
        }

        $results['total_hours'] = $totalhours;

        return $results;
    }

    // Deprecated - Quickbooks removed import capability in desktop versions after 2023
    public function getResultsIIF($fromDate, $toDate, $companyProfileId = null): array
    {
        $results = [
            'from_date' => '',
            'to_date' => '',
            'records' => [],
        ];

        $invoices = Invoice::select(DB::raw('"TIMEACT" AS TIMEACT'),
            DB::raw('DATE_FORMAT(documents.document_date,"%m/%d/%y") AS DATE'),
            DB::raw('NULL AS JOB'),
            DB::raw('CONCAT_WS(", ",employees.last_name, employees.first_name) AS EMP'),
            DB::raw('NULL AS ITEM'),
            DB::raw('"Hourly Wage" AS PITEM'),
            DB::raw('ROUND(document_items.quantity,2) AS DURATION'),
            DB::raw('NULL AS PROJ'),
            DB::raw('NULL AS NOTE'),
            DB::raw('"0" AS BILLINGSTATUS'))
            ->join('document_items', 'document_items.document_id', '=', 'documents.id')
            ->join('clients', 'clients.id', '=', 'documents.client_id')
            ->join('employees', 'employees.id', '=', 'document_items.resource_id')
            ->whereBetween('document_date', [$fromDate, $toDate])
            ->where('document_items.resource_table', 'employees')
            ->orderBy('EMP', 'ASC')
            ->orderBy('DATE', 'ASC');

        if ($companyProfileId) {
            $companyProfile = CompanyProfile::where('id', $companyProfileId)->first();
            $results['companyProfile_company'] = $companyProfile->company;

            $invoices->where('company_profile_id', $companyProfileId);
        } else {
            $results['companyProfile_company'] = 'All Billing';
            $results['TSCompanyCreate'] = config('bt.tsCompanyCreate');
            $results['TSCompanyName'] = config('bt.tsCompanyName');
        }

        $invoices = $invoices->get();

        $results['from_date'] = DateFormatter::format($fromDate);
        $results['to_date'] = DateFormatter::format($toDate);

        if (! count($invoices)) {
            return $results;
        }

        foreach ($invoices as $invoice) {
            $results['records'][] = [
                'TIMEACT' => $invoice->TIMEACT,
                'DATE' => $invoice->DATE,
                'JOB' => $invoice->JOB,
                'EMP' => $invoice->EMP,
                'ITEM' => $invoice->ITEM,
                'PITEM' => $invoice->PITEM,
                'DURATION' => $invoice->DURATION,
                'PROJ' => $invoice->PROJ,
                'NOTE' => $invoice->NOTE,
                'BILLINGSTATUS' => $invoice->BILLINGSTATUS,
            ];

        }

        return $results;
    }
}
