<?php

/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Reports\Reports;

use BT\Modules\Documents\Models\Invoice;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Support\DateFormatter;
use DB;

class TimeSheetReport {
	public function getResults( $fromDate, $toDate, $companyProfileId = null, $report_type = null ) {
		$results = [
			'from_date' => '',
			'to_date'   => '',
			'total_records',
			'total_hours'   => '',
			'records'   => [],
		];

        if ($report_type == 'condensed'){
            $invoices = Invoice::select('document_items.document_id AS InvoiceID', 'documents.number AS InvoiceNumber',
                'clients.name AS CustomerName', 'document_items.name AS ItemName',
                DB::raw('sum(document_items.quantity) AS ItemQty'), 'employees.number AS EmpNumber',
                'documents.document_date AS DateFinished', DB::raw('CONCAT(employees.last_name,", ",employees.first_name) AS FullName'))
                ->join('document_items', 'document_items.document_id', '=', 'documents.id')
                ->join('clients', 'clients.id', '=', 'documents.client_id')
                ->join('employees', 'employees.id', '=', 'document_items.resource_id')
                ->whereBetween('document_date', [$fromDate, $toDate])
                ->where('document_items.resource_table', 'employees')
                ->groupBy('EmpNumber')
                ->orderBy('FullName', 'ASC');

        }else {
            $invoices = Invoice::select('document_items.document_id AS InvoiceID', 'documents.number AS InvoiceNumber',
                'clients.name AS CustomerName', 'document_items.name AS ItemName',
                'document_items.quantity AS ItemQty', 'employees.number AS EmpNumber',
                'documents.document_date AS DateFinished', DB::raw('CONCAT(employees.last_name,", ",employees.first_name) AS FullName'))
                ->join('document_items', 'document_items.document_id', '=', 'documents.id')
                ->join('clients', 'clients.id', '=', 'documents.client_id')
                ->join('employees', 'employees.id', '=', 'document_items.resource_id')
                ->whereBetween('document_date', [$fromDate, $toDate])
                ->where('document_items.resource_table', 'employees')
                ->orderBy('FullName', 'ASC')
                ->orderBy('DateFinished', 'ASC');
        }
		if ( $companyProfileId ) {
			$companyProfile = CompanyProfile::where( 'id', $companyProfileId )->first();
			$results['companyProfile_company'] = $companyProfile->company;

			$invoices->where( 'company_profile_id', $companyProfileId );
		} else {
			$results['companyProfile_company'] = 'All Billing';
		}

		$invoices = $invoices->get();

		$results['from_date'] = DateFormatter::format( $fromDate );
		$results['to_date']   = DateFormatter::format( $toDate );
		$results['total_records'] = count($invoices);
        $results['report_type'] = $report_type;

		if ( ! count( $invoices ) ) {

			return $results;
		}

		$totalhours = $invoices->sum( 'ItemQty' );

		foreach ( $invoices as $invoice ) {
			$results['records'][] = [
				'number'                 => $invoice->InvoiceNumber,
				'client_name'            => $invoice->CustomerName,
				'formatted_document_date' => $invoice->DateFinished,
				'item_name'              => $invoice->ItemName,
				'item_qty'               => $invoice->ItemQty,
				'full_name'              => $invoice->FullName,
				'employee_number'        => $invoice->EmpNumber,
			];

		}

		$results['total_hours'] = $totalhours;

		return $results;
	}

	public function getResultsIIF( $fromDate, $toDate, $companyProfileId = null ) {
		$results = [
			'from_date' => '',
			'to_date'   => '',
			'records'   => [],
		];

		$invoices = Invoice::select( DB::raw( '"TIMEACT" AS TIMEACT' ),
			DB::raw( 'DATE_FORMAT(documents.document_date,"%m/%d/%y") AS DATE' ),
			DB::raw( 'NULL AS JOB' ),
			DB::raw( 'CONCAT_WS(", ",employees.last_name, employees.first_name) AS EMP' ),
			DB::raw( 'NULL AS ITEM' ),
			DB::raw( '"Hourly Wage" AS PITEM' ),
			DB::raw( 'ROUND(document_items.quantity,2) AS DURATION' ),
			DB::raw( 'NULL AS PROJ' ),
			DB::raw( 'NULL AS NOTE' ),
			DB::raw( '"0" AS BILLINGSTATUS' ) )
		                   ->join( 'document_items', 'document_items.document_id', '=', 'documents.id' )
		                   ->join( 'clients', 'clients.id', '=', 'documents.client_id' )
		                   ->join( 'employees', 'employees.id', '=', 'document_items.resource_id' )
		                   ->whereBetween( 'document_date', [ $fromDate, $toDate ] )
		                   ->where( 'document_items.resource_table', 'employees' )
		                   ->orderBy( 'EMP', 'ASC' )
		                   ->orderBy( 'DATE', 'ASC' );


		if ( $companyProfileId ) {
			$companyProfile = CompanyProfile::where( 'id', $companyProfileId )->first();
			$results['companyProfile_company'] = $companyProfile->company;

			$invoices->where( 'company_profile_id', $companyProfileId );
		} else {
			$results['companyProfile_company'] = 'All Billing';
			$results['TSCompanyCreate'] = config('bt.tsCompanyCreate');
			$results['TSCompanyName'] = config('bt.tsCompanyName');
		}

		$invoices = $invoices->get();

		$results['from_date'] = DateFormatter::format( $fromDate );
		$results['to_date']   = DateFormatter::format( $toDate );

		if ( ! count( $invoices ) ) {
			return $results;
		}

		foreach ( $invoices as $invoice ) {
			$results['records'][] = [
				'TIMEACT'                 => $invoice->TIMEACT,
				'DATE'            => $invoice->DATE,
				'JOB' => $invoice->JOB,
				'EMP'              => $invoice->EMP,
				'ITEM'               => $invoice->ITEM,
				'PITEM'              => $invoice->PITEM,
				'DURATION'        => $invoice->DURATION,
				'PROJ'        => $invoice->PROJ,
				'NOTE'        => $invoice->NOTE,
				'BILLINGSTATUS'        => $invoice->BILLINGSTATUS,
			];

		}

		return $results;
	}
}
