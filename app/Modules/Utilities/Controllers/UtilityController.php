<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Utilities\Controllers;

use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\Quotes\Models\Quote;
use BT\Modules\Workorders\Models\Workorder;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use Illuminate\Http\Request;

class UtilityController
{
    public function manageTrash()
    {
        return view('utilities.trash');
    }

    /**
     * @param $id
     * @param $entity fully qualified classname passed from _action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restoreTrash($id, $entity)
    {
        $entity::onlyTrashed()->find($id)->restore();

        return back()->with('alertSuccess', trans('bt.record_successfully_restored'));
    }

    /**
     * @param $id
     * @param $entity fully qualified classname passed from _action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteTrash($id, $entity)
    {
        $entity::onlyTrashed()->find($id)->forceDelete();

        return back()->with('alertSuccess', trans('bt.record_successfully_deleted'));
    }

    public function bulkDeleteTrash()
    {
        $ids = request('ids');
        $module_type = request('module_type');
        if ($module_type == 'Schedule') {
            $module_model = 'BT\\Modules\\Scheduler\\Models\\' . $module_type;
        } elseif ($module_type == 'TimeTrackingProject') {
            $module_model = 'BT\\Modules\\TimeTracking\\Models\\' . $module_type;
        } else {
            $module_model = 'BT\\Modules\\' . $module_type . 's\\Models\\' . $module_type;
        }

        foreach ($ids as $id) {
            $module_model::onlyTrashed()->find($id)->forceDelete();
        }

        return response()->json(['success' => trans('bt.record_successfully_deleted')], 200);
    }

    public function bulkRestoreTrash()
    {
        $ids = request('ids');
        $module_type = request('module_type');
        if ($module_type == 'Schedule') {
            $module_model = 'BT\\Modules\\Scheduler\\Models\\' . $module_type;
        } elseif ($module_type == 'TimeTrackingProject') {
            $module_model = 'BT\\Modules\\TimeTracking\\Models\\' . $module_type;
        } else {
            $module_model = 'BT\\Modules\\' . $module_type . 's\\Models\\' . $module_type;
        }

        foreach ($ids as $id) {
            $module_model::onlyTrashed()->find($id)->restore();
        }

        return response()->json(['success' => trans('bt.record_successfully_restored')], 200);
    }

    public function batchPrint(Request $request, $module = null)
    {
        if ($request->isMethod('post')) {
            $start = $request->from_date;
            $end = $request->to_date;

            switch ($request->batch_type) {
                case 'quotes':
                    //quotes sent or approved, not converted to workorder or invoice
                    $batchtypes = Quote::whereBetween('quote_date', [$start, $end])
                        ->whereBetween('quote_status_id', [2, 3])
                        ->where('invoice_id', 0)->where('workorder_id', 0)->get();
                    break;
                case 'workorders':
                    //workorders sent or approved, not converted to invoice
                    $batchtypes = Workorder::whereBetween('job_date', [$start, $end])
                        ->whereBetween('workorder_status_id', [2, 3])
                        ->where('invoice_id', 0)->get();
                    break;
                case 'invoices':
                    //invoices sent (not paid)
                    $batchtypes = Invoice::whereBetween('invoice_date', [$start, $end])
                        ->where('invoice_status_id', 2)->get();
                    break;
            }


            if (!count($batchtypes)) {
                return redirect()->back()->with('alert', trans('bt.batch_nodata_alert'));
            }

            $pdf = PDFFactory::create();
            $wohtml = [];
            $counter = 1;
            foreach ($batchtypes as $batchtype) {
                $wohtml[$counter] = $batchtype->html;
                $counter++;
            }

            $pdf->download($wohtml, FileNames::batchprint());

        } else {
            return view('utilities.batchprint', ['module' => $module]);
        }
    }

    public function saveTab()
    {
        session(['trashTabId' => request('trashTabId')]);
    }

}
