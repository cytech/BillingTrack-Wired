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
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Documents\Models\Document;
use BT\Support\FileNames;
use BT\Support\PDF\PDFFactory;
use BT\Support\Statuses\DocumentStatuses;
use BT\Traits\ReturnUrl;

class DocumentController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();
        $status = request('status') === 'all_statuses' ? '' : request('status');
        $module = request('module_type');
        switch ($module){
            case 1:
                $module_type = DOCUMENT_TYPE_QUOTE['module_type'];
                $modulefullname = DOCUMENT_TYPE_QUOTE['modulefullname'];
                break;
            case 2:
                $module_type = DOCUMENT_TYPE_WORKORDER['module_type'];
                $modulefullname = DOCUMENT_TYPE_WORKORDER['modulefullname'];
                break;
            case 3:
                $module_type = DOCUMENT_TYPE_INVOICE['module_type'];
                $modulefullname = DOCUMENT_TYPE_INVOICE['modulefullname'];
                break;
        }

        //$module_type == 1 ? $module_type = 'Quote' : $module_type = null;
        return view('documents.index')->with('status', $status)->with('module_type', $module_type)->with('modulefullname', $modulefullname);
    }

    public function delete($id)
    {
        Document::destroy($id);

//        return redirect()->route('documents.index')
//            ->with('alert', trans('bt.record_successfully_trashed'));
        return back()
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
}
