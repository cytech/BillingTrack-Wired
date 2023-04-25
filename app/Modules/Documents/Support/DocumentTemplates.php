<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Support;

use BT\Support\Directory;

class DocumentTemplates
{
    /**
     * Returns an array of quote templates.
     *
     * @return array
     */
    public static function lists($module_type)
    {
        switch ($module_type) {
            case 'Document':
                $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/documents'));
                $customTemplates = Directory::listAssocContents(base_path('custom/templates/document_templates'));
                break;
            case 'Quote':
                $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/quotes'));
                $customTemplates = Directory::listAssocContents(base_path('custom/templates/quote_templates'));
                break;
            case 'Workorder':
                $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/workorders'));
                $customTemplates = Directory::listAssocContents(base_path('custom/templates/workorder_templates'));
                break;
            case 'Invoice':
            case 'Recurringinvoice':
                $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/invoices'));
                $customTemplates = Directory::listAssocContents(base_path('custom/templates/invoice_templates'));
                break;
            case 'Purchaseorder':
                $defaultTemplates = Directory::listAssocContents(app_path('Modules/Templates/Views/templates/purchaseorders'));
                $customTemplates = Directory::listAssocContents(base_path('custom/templates/purchaseorder_templates'));
                break;
        }

        return $defaultTemplates + $customTemplates;
    }
}
