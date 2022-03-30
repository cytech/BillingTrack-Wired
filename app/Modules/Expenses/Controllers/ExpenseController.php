<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Expenses\Controllers;

use BT\Http\Controllers\Controller;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Categories\Models\Category;
use BT\Modules\Expenses\Requests\ExpenseRequest;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\NumberFormatter;
use BT\Traits\ReturnUrl;

class ExpenseController extends Controller
{
    use ReturnUrl;

    public function index()
    {
        $this->setReturnUrl();
        $status = request('status');
        $categories = ['' => trans('bt.all_categories')] + Category::getList();
        $vendors = ['' => trans('bt.all_vendors')] + Vendor::getList();
        $statuses = ['' => trans('bt.all_statuses'), 'billed' => trans('bt.billed'), 'not_billed' => trans('bt.not_billed'), 'not_billable' => trans('bt.not_billable')];
        $companyProfiles = ['' => trans('bt.all_company_profiles')] + CompanyProfile::getList();

        return view('expenses.index', compact('status', 'categories', 'vendors', 'statuses', 'companyProfiles'));

    }

    public function create()
    {
        return view('expenses.form')
            ->with('editMode', false)
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('categories', Category::pluck('name', 'id'))
            ->with('currentDate', date('Y-m-d'))
            ->with('customFields', CustomField::forTable('expenses')->get());
    }

    public function store(ExpenseRequest $request)
    {
        $record = request()->except('attachments', 'custom');

        $record['amount'] = NumberFormatter::unformat($record['amount']);
        $record['tax'] = ($record['tax']) ? NumberFormatter::unformat($record['tax']) : 0;

        $record['category_id'] = Category::firstOrCreate(['name' => $request->category_name])->id;

        if ($request->vendor_name) {
            $record['vendor_id'] = Vendor::firstOrCreate(['name' => $request->vendor_name])->id;
        } else {
            $record['vendor_id'] = 0;
        }

        if ($request->client_name) {
            $record['client_id'] = Client::firstOrCreateByName($request->client_id, $request->client_name)->id;
        } else {
            $record['client_id'] = 0;
        }
        $expense = Expense::create($record);

        $expense->custom->update(request('custom', []));

        return redirect($this->getReturnUrl())
            ->with('alertSuccess', trans('bt.record_successfully_created'));
    }

    public function edit($id)
    {
        return view('expenses.form')
            ->with('editMode', true)
            ->with('companyProfiles', CompanyProfile::getList())
            ->with('categories', Category::pluck('name', 'id'))
            ->with('expense', Expense::defaultQuery()->find($id))
            ->with('customFields', CustomField::forTable('expenses')->get());
    }

    public function update(ExpenseRequest $request, $id)
    {
        $record = request()->except('attachments', 'custom');

        $record['amount'] = NumberFormatter::unformat($record['amount']);
        $record['tax'] = ($record['tax']) ? NumberFormatter::unformat($record['tax']) : 0;

        $record['category_id'] = Category::firstOrCreate(['name' => $request->category_name])->id;

        if ($request->vendor_name) {
            $record['vendor_id'] = Vendor::firstOrCreate(['name' => $request->vendor_name])->id;
        } else {
            $record['vendor_id'] = 0;
        }

        if ($request->client_name) {
            $record['client_id'] = Client::firstOrCreateByName($request->client_id, $request->client_name)->id;
        } else {
            $record['client_id'] = 0;
        }

        $expense = Expense::find($id);

        $expense->fill($record);

        $expense->save();

        $expense->custom->update(request('custom', []));

        return redirect($this->getReturnUrl())
            ->with('alertSuccess', trans('bt.record_successfully_updated'));
    }


    public function delete($id)
    {
        Expense::destroy($id);

        return redirect($this->getReturnUrl())
            ->with('alertInfo', trans('bt.record_successfully_deleted'));
    }

    public function bulkDelete()
    {
        Expense::destroy(request('ids'));
        return response()->json(['success' => trans('bt.record_successfully_trashed')], 200);

    }
}
