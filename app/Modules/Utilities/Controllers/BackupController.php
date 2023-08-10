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

use BT\Http\Controllers\Controller;
use BT\Modules\Clients\Models\Client;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Recurringinvoice;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\Scheduler\Models\Schedule;
use BT\Modules\TimeTracking\Models\TimeTrackingProject;
use Ifsnop\Mysqldump\Mysqldump;
use Illuminate\Http\Request;

class BackupController extends Controller
{
    public function index()
    {
        return view('utilities._backup')
            ->with('quotecount', Quote::onlyTrashed()->count())
            ->with('workordercount', Workorder::onlyTrashed()->count())
            ->with('invoicecount', Invoice::onlyTrashed()->count())
            ->with('purchaseordercount', Purchaseorder::onlyTrashed()->count())
            ->with('schedulecount', Schedule::onlyTrashed()->count())
            ->with('paymentcount', Payment::onlyTrashed()->count());
    }

    public function database()
    {
        $default = config('database.default');
        $host = config('database.connections.'.$default.'.host');
        $dbname = config('database.connections.'.$default.'.database');
        $username = config('database.connections.'.$default.'.username');
        $password = config('database.connections.'.$default.'.password');
        $filename = storage_path('BillingTrack_'.date('Y-m-d_H-i-s').'.sql');

        $dump = new Mysqldump('mysql:host='.$host.';dbname='.$dbname, $username, $password);
        $dump->start($filename);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function trashPrior(Request $request)
    {
        $maxtime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $date = $request->trashprior_date;
        $module = $request->trashprior_module;
        $msg = '';
        if ($module == 'Schedule') {
            $className = '\BT\Modules\\'.$module.'r\Models\\'.$module;
            $records = $className::whereHas('occurrences', function ($q) use ($date) {
                $q->where('end_date', '<', $date)->whereNull('deleted_at');
            });
            if ($records) {
                $msg = $records->count().' '.trans('bt.record_successfully_trashed');
                $records->delete();
            } else {
                $msg = '0 '.trans('bt.record_successfully_trashed');
            }
        } elseif ($module == 'Payment') {
            $className = '\BT\Modules\\'.$module.'s\Models\\'.$module;
            $records = $className::where('created_at', '<', $date)->whereNull('deleted_at');
            if ($records) {
                $msg = $records->count().' '.trans('bt.record_successfully_trashed');
                $records->delete();
            } else {
                $msg = '0 '.trans('bt.record_successfully_trashed');
            }
        } else {
            $className = '\BT\Modules\Documents\Models\\'.$module;
            $datefield = 'document_date';
            $records = $className::where($datefield, '<', $date)->whereNull('deleted_at');
            if ($records) {
                $msg = $records->count().' '.trans('bt.record_successfully_trashed');
                $records->delete();
            } else {
                $msg = '0 '.trans('bt.record_successfully_trashed');
            }
        }

        ini_set('max_execution_time', $maxtime);

        return back()->with('alertSuccess', $msg);
    }

    public function deletePrior(Request $request)
    {
        $maxtime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);

        $date = $request->deleteprior_date;
        $module = $request->deleteprior_module;

        if ($module == 'Schedule') {
            $className = '\BT\Modules\\'.$module.'r\Models\\'.$module;
            $className::whereHas('occurrences', function ($q) use ($date) {
                $q->onlyTrashed()->where('end_date', '<', $date);
            })->onlyTrashed()->forceDelete();
        } elseif ($module == 'Payment') {
            $className = '\BT\Modules\\'.$module.'s\Models\\'.$module;
            $className::where('created_at', '<', $date)->onlyTrashed()->forceDelete();
        } else {
            $className = '\BT\Modules\Documents\Models\\'.$module;
            $datefield = 'document_date';
            // this was deleteing all doc types ()something in parental??....
            //$className::where($datefield, '<', $date)->onlyTrashed()->forceDelete();
            $docs = $className::where($datefield, '<', $date)->onlyTrashed()->get();
            foreach ($docs as $doc) {
                $doc->forceDelete();
            }
        }

        ini_set('max_execution_time', $maxtime);

        return back()->with('alertSuccess', trans('bt.record_successfully_deleted'));
    }

    public function clientInactivePrior(Request $request)
    {
        $maxtime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);

        $date = $request->clientprior_date;
        // find all clients with activity after $date
        $active_quote = Quote::distinct('client_id')->where('document_date', '>=', $date)->pluck('client_id', 'client_id');
        $active_workorder = Workorder::distinct('client_id')->where('document_date', '>=', $date)->pluck('client_id', 'client_id');
        $active_invoice = Invoice::distinct('client_id')->where('document_date', '>=', $date)->pluck('client_id', 'client_id');
        $active_recurringinvoice = Recurringinvoice::distinct('client_id')->where('next_date', '>=', $date)->pluck('client_id', 'client_id');
        $active_payment = Payment::distinct('client_id')->where('paid_at', '>=', $date)->pluck('client_id', 'client_id');
        $active_expense = Expense::distinct('client_id')->where('expense_date', '>=', $date)->pluck('client_id', 'client_id');
        $active_project = TimeTrackingProject::distinct('client_id')->where('due_at', '>=', $date)->pluck('client_id', 'client_id');
        //union above
        $results = $active_quote->union($active_workorder)->union($active_invoice)->union($active_recurringinvoice)
            ->union($active_payment)->union($active_expense)->union($active_project);
        //get all clients
        $clients = Client::pluck('id', 'id');
        //get client ids to set as inactive
        $inactiveclients = $clients->diff($results);

        $setinactive = Client::whereIn('id', $inactiveclients)->where('active', 1)->get();

        foreach ($setinactive as $client) {
            $client->active = 0;
            $client->save();
        }

        ini_set('max_execution_time', $maxtime);

        return back()->with('alertSuccess', $setinactive->count().' Clients set to Inactive');

    }

    // below for database debugging
    // v6 to v7
    // v7 migration sanitizes orphans so these should not be necessary
    // possible backport or addon for V6removeOrphanedModules...
    public function removeOrphanedModules()
    {
        $maxtime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $msg = '';
        $className = '\BT\Modules\Documents\Models\DocumentAmount';
        $records = $className::withTrashed()->whereDoesntHave('document', function ($query) {
            return $query->withTrashed();
        });
        if ($records) {
            //            $msg .= $records->count() . ' ' . trans('bt.orphan_amounts_found') . "<br />";
            $msg .= $records->count().' '.trans('Orphan Amounts Found').'<br />';
            //            $records->forceDelete();
        } else {
            //            $msg .= '0 ' . trans('bt.orphan_amounts_found') . "<br />";
            $msg .= '0 '.trans('Orphan Amounts Found').'<br />';
        }

        $className = '\BT\Modules\Documents\Models\DocumentItemAmount';
        $records = $className::withTrashed()->whereDoesntHave('item', function ($query) {
            return $query->withTrashed();
        });
        if ($records) {
            //            $msg .= $records->count() . ' ' . trans('bt.orphan_item_amounts_found') . "<br />";
            $msg .= $records->count().' '.trans('Orphan ItemAmounts Found').'<br />';
            //            $records->forceDelete();
        } else {
            //            $msg .= '0 ' . trans('bt.orphan_item_amounts_found') . "<br />";
            $msg .= '0 '.trans('Orphan ItemAmounts Found').'<br />';
        }

        $className = '\BT\Modules\Documents\Models\DocumentItem';
        $records = $className::withTrashed()->whereDoesntHave('document', function ($query) {
            return $query->withTrashed();
        });
        if ($records) {
            //            $msg .= $records->count() . ' ' . trans('bt.orphan_items_found') . "<br />";
            $msg .= $records->count().' '.trans('Orphan Items Found').'<br />';
            //            $records->forceDelete();
        } else {
            //            $msg .= '0 ' . trans('bt.orphan_items_found') . "<br />";
            $msg .= '0 '.trans('Orphan Items Found').'<br />';
        }

        ini_set('max_execution_time', $maxtime);

        return back()->with('error', $msg);

    }

    public function V6removeOrphanedModules()
    {
        $maxtime = ini_get('max_execution_time');
        ini_set('max_execution_time', 0);
        $coredoctypes = ['Quote', 'Workorder', 'Invoice', 'Purchaseorder', 'RecurringInvoice'];

        $msg = '';
        foreach ($coredoctypes as $coredoctype) {
            $modtype = '\\BT\Support\\SixtoSeven\\Models\\'.$coredoctype.'Amount';

            $records = $modtype::withTrashed()->whereDoesntHave(strtolower($coredoctype), function ($query) {
                return $query->withTrashed();
            });

            if ($records) {
                //                $msg .= $records->count() . ' ' . trans('bt.orphan_amounts_found') . "<br />";
                $msg .= $records->count().' '.$coredoctype.' Amount orphans found '.'<br />';
                //            $records->forceDelete();
            } else {
                //                $msg .= '0 ' . trans('bt.orphan_amounts_found') . "<br />";
                $msg .= '0 '.$coredoctype.' Amount orphans found '.'<br />';
            }

            $modtype = '\\BT\Support\\SixtoSeven\\Models\\'.$coredoctype.'ItemAmount';
            $records = $modtype::withTrashed()->whereDoesntHave('item', function ($query) {
                return $query->withTrashed();
            });
            if ($records) {
                //                $msg .= $records->count() . ' ' . trans('bt.orphan_item_amounts_found') . "<br />";
                $msg .= $records->count().' '.$coredoctype.' Item Amount orphans found '.'<br />';
                //            $records->forceDelete();
            } else {
                //                $msg .= '0 ' . trans('bt.orphan_item_amounts_found') . "<br />";
                $msg .= '0 '.$coredoctype.' Item Amount orphans found '.'<br />';
            }

            $modtype = '\\BT\Support\\SixtoSeven\\Models\\'.$coredoctype.'Item';
            $records = $modtype::withTrashed()->whereDoesntHave(strtolower($coredoctype), function ($query) {
                return $query->withTrashed();
            });
            if ($records) {
                //                $msg .= $records->count() . ' ' . trans('bt.orphan_items_found') . "<br />";
                $msg .= $records->count().' '.$coredoctype.' Item orphans found '.'<br />';
                //            $records->forceDelete();
            } else {
                //                $msg .= '0 ' . trans('bt.orphan_items_found') . "<br />";
                $msg .= '0 '.$coredoctype.' Item orphans found '.'<br />';
            }

        }
        ini_set('max_execution_time', $maxtime);

        return back()->with('error', $msg);

    }
}
