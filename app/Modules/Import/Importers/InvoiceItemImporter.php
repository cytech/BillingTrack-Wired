<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Import\Importers;

use BT\Events\InvoiceModified;
use BT\Modules\Invoices\Models\Invoice;
use BT\Modules\TaxRates\Models\TaxRate;
use Illuminate\Support\Facades\Validator;

class InvoiceItemImporter extends AbstractImporter
{
    public function getFields()
    {
        return [
            'invoice_id'    => '* ' . trans('bt.invoice_number'),
            'name'          => '* ' . trans('bt.product'),
            'quantity'      => '* ' . trans('bt.quantity'),
            'price'         => '* ' . trans('bt.price'),
            'description'   => trans('bt.description'),
            'tax_rate_id'   => trans('bt.tax_1'),
            'tax_rate_2_id' => trans('bt.tax_2'),
        ];
    }

    public function getMapRules()
    {
        return [
            'invoice_id' => 'required',
            'name'       => 'required',
            'quantity'   => 'required',
            'price'      => 'required',
        ];
    }

    public function getValidator($input)
    {
        return Validator::make($input, [
                'invoice_id' => 'required',
                'name'       => 'required',
                'quantity'   => 'required|numeric',
                'price'      => 'required|numeric',
            ]
        );
    }

    public function importData($input)
    {
        $row = 1;

        $fields = [];

        $taxRates = TaxRate::get();

        foreach ($input as $field => $key)
        {
            if (is_numeric($key))
            {
                $fields[$key] = $field;
            }
        }

        $handle = fopen(storage_path('invoiceItems.csv'), 'r');

        if (!$handle)
        {
            $this->messages->add('error', 'Could not open the file');

            return false;
        }

        while (($data = fgetcsv($handle, 1000, ',')) !== false)
        {
            if ($row !== 1)
            {
                $record = [];

                foreach ($fields as $key => $field)
                {
                    $record[$field] = $data[$key];
                }

                $invoice = Invoice::where('number', $record['invoice_id'])->first();

                if ($invoice)
                {
                    $record['invoice_id'] = $invoice->id;

                    if (!isset($record['tax_rate_id']))
                    {
                        $record['tax_rate_id'] = 0;
                    }
                    else
                    {
                        if ($taxRate = $taxRates->where('name', $record['tax_rate_id'])->first())
                        {
                            $record['tax_rate_id'] = $taxRate->id;
                        }
                        else
                        {
                            $record['tax_rate_id'] = 0;
                        }
                    }

                    if (!isset($record['tax_rate_2_id']))
                    {
                        $record['tax_rate_2_id'] = 0;
                    }
                    else
                    {
                        if ($taxRate = $taxRates->where('name', $record['tax_rate_2_id'])->first())
                        {
                            $record['tax_rate_2_id'] = $taxRate->id;
                        }
                        else
                        {
                            $record['tax_rate_2_id'] = 0;
                        }
                    }

                    $record['display_order'] = 0;

                    if ($this->validateRecord($record))
                    {
                        if (!isset($record['description'])) $record['description'] = '';

                        $invoice->items()->create($record);

                        event(new InvoiceModified($invoice));
                    }
                }
            }

            $row++;
        }

        fclose($handle);

        return true;
    }
}
