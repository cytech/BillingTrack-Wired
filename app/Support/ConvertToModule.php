<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support;

use BT\Modules\CustomFields\Models\CustomField;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\Groups\Models\Group;

class ConvertToModule
{
    public function convert($module, $moduleDate, $dueAt, $groupId, $toModuleType)
    {
        $moduleType = class_basename($module);
        //        $toModuleModel = 'BT\Modules\\' . $toModuleType . 's\Models\\' . $toModuleType;
        //        $toModuleStatuses = 'BT\Support\Statuses\\' . $toModuleType . 'Statuses';
        //        $toModuleEvent = 'BT\Events\\' . $toModuleType . 'Modified';
        //        $toModuleItem = 'BT\Modules\\' . $toModuleType . 's\Models\\' . $toModuleType . 'Item';
        //        $moduleStatuses = 'BT\Support\Statuses\\' . $moduleType . 'Statuses';
        $toModuleModel = 'BT\Modules\Documents\Models\Document';
        $toModuleStatuses = 'BT\Support\Statuses\DocumentStatuses';
        $toModuleEvent = 'BT\Events\DocumentModified';
        $toModuleItem = 'BT\Modules\Documents\Models\DocumentItem';
        $moduleStatuses = 'BT\Support\Statuses\DocumentStatuses';
        $module_id = strtolower($toModuleType).'_id';
        //        $module_status_id = strtolower($moduleType) . '_status_id';
        $module_status_id = 'document_status_id';
        //        $module_items = strtolower($moduleType) . 'Items';
        $module_items = 'documentItems';

        $record = [
            'client_id' => $module->client_id,
            'group_id' => $groupId,
            'number' => Group::generateNumber($groupId),
            'user_id' => $module->user_id,
            'terms' => ((config('bt.convertQuoteTerms') == 'quote') ? $module->terms : config('bt.invoiceTerms')),
            'footer' => $module->footer,
            'currency_code' => $module->currency_code,
            'exchange_rate' => $module->exchange_rate,
            'summary' => $module->summary,
            'discount' => $module->discount,
            'company_profile_id' => $module->company_profile_id,
        ];

        if ($toModuleType == 'Invoice') {
            $record['document_type'] = Invoice::class;
            $record['document_date'] = $moduleDate;
            $record['action_date'] = $dueAt;
            $record['document_status_id'] = $toModuleStatuses::getStatusId('draft');
        } else { //module workorder
            $record['document_type'] = Workorder::class;
            $record['document_date'] = $moduleDate;
            $record['action_date'] = $dueAt;
            $record['document_status_id'] = $toModuleStatuses::getStatusId('draft');
        }

        $toModule = $toModuleModel::create($record);

        CustomField::copyCustomFieldValues($module, $toModule);

        $module->$module_id = $toModule->id;
        $module->$module_status_id = $moduleStatuses::getStatusId('approved');
        $module->save();

        foreach ($module->$module_items as $item) {
            $itemRecord = [
                //                $module_id       => $toModule->id,
                'document_id' => $toModule->id,
                'name' => $item->name,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'tax_rate_id' => $item->tax_rate_id,
                'tax_rate_2_id' => $item->tax_rate_2_id,
                'resource_table' => $item->resource_table,
                'resource_id' => $item->resource_id,
                'display_order' => $item->display_order,
            ];

            $toModuleItem::create($itemRecord);
        }

        event(new $toModuleEvent($toModule));

        return $toModule;
    }
}
