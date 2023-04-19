<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Workorder;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\DocumentStatuses;
use BT\Support\Statuses\PurchaseorderItemStatuses;
use BT\Traits\ReturnUrl;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();
        $status = request('status') === 'all_statuses' ? '' : request('status');
        $module = request('module_type');
        switch ($module) {
            case 'Quote':
                $module_type = $module;
                $modulefullname = addslashes(Quote::class);
                break;
            case 'Workorder':
                $module_type = $module;
                $modulefullname = addslashes(Workorder::class);
                break;
            case 'Invoice':
                $module_type = $module;
                $modulefullname = addslashes(Invoice::class);
                break;
            case 'Purchaseorder':
                $module_type = $module;
                $modulefullname = addslashes(Purchaseorder::class);
                break;
        }

        return view('documents.index')->with('status', $status)->with('module_type', $module_type)->with('modulefullname', $modulefullname);
    }

    public function delete($id)
    {
        Document::destroy($id);

        return redirect()->route('documents.index', ['module_type' => \request('module_type')])
            ->with('alert', trans('bt.record_successfully_trashed'));
    }

    public function bulkDelete()
    {
        Document::destroy(request('ids'));
        return response()->json(['success' => trans('bt.record_successfully_trashed')], 200);
    }

    public function bulkStatus()
    {
        Document::whereIn('id', request('ids'))->update(['document_status_id' => request('status')]);

        return response()->json(['success' => trans('bt.status_successfully_updated')], 200);
    }

    public function pdf($id)
    {
        $document = Document::find($id);

        $pdf = PDFFactory::create();

        $pdf->download($document->html, FileNames::document($document));
    }

    public function receive()
    {

        $items = DocumentItem::where('document_id', request('purchaseorder_id'))->get();
        return view('documents._modal_receive')
            ->with('items', $items);
    }

    public function receiveItems(Request $request)
    {
        $items = DocumentItem::whereIn('id', $request->itemrec_ids)->get();

        $rec_cnt = 0;
        $rec_qty = 0;

        // update received info
        foreach ($items as $item) {
            foreach ($request->itemrec_att as $att) {
                if ($att['id'] == $item->id) {
                    $qty = $item->rec_qty + $att['rec_qty'];
                    $cost = $att['rec_cost'];
                    $rec_qty = $att['rec_qty'];

                    if ($qty == $item->quantity) {
                        $status_id = PurchaseorderItemStatuses::getStatusId('received');
                        $rec_cnt++;
                    } elseif ($qty == 0) {
                        $status_id = PurchaseorderItemStatuses::getStatusId('open');
                    } elseif ($qty < $item->quantity) {
                        $status_id = PurchaseorderItemStatuses::getStatusId('partial');
                    } elseif ($qty > $item->quantity) {
                        $status_id = PurchaseorderItemStatuses::getStatusId('extra');
                    } else {
                        $status_id = PurchaseorderItemStatuses::getStatusId('canceled');
                    }
                }
            }

            $item->rec_status_id = $status_id;
            $item->rec_qty = $qty;
            $item->price = $cost;
            $item->save();

            //if update products is checked
            if ($request->itemrec) {
                //update product table quantities and cost for items
                if ($item->resource_table == 'products' && $item->resource_id) {
                    $item->product->increment('numstock', $rec_qty, ['cost' => $item->price]);
                }
            }
        }

        // change PO status to received/partial
        $purchaseorder = Purchaseorder::where('id', $items->first()->document_id)->first();
        if ($rec_cnt == $items->count()) {
            $purchaseorder->document_status_id = DocumentStatuses::getStatusId('received');
        } else {
            $purchaseorder->document_status_id = DocumentStatuses::getStatusId('partial');
        }
        $purchaseorder->save();
    }
}
