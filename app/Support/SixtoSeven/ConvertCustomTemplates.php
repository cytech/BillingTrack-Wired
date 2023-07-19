<?php

namespace BT\Support\SixtoSeven;

use BT\Support\Directory;
use Illuminate\Support\Facades\File;

class ConvertCustomTemplates
{
    /**
     * copy user custom templates
     * copies existing custom templates to V6Backup directory
     * path custom/templates/
     * quote_templates, workorder_templates, invoice_templates, purchaseorder_templates
     * @return void
     */
    public static function copy(): void
    {
        if (!File::exists(base_path('custom/templates/V6Backup'))) {
            File::copyDirectory(base_path('custom/templates/quote_templates'), base_path('custom/templates/V6Backup/quote_templates'));
            File::copyDirectory(base_path('custom/templates/workorder_templates'), base_path('custom/templates/V6Backup/invoice_templates'));
            File::copyDirectory(base_path('custom/templates/invoice_templates'), base_path('custom/templates/V6Backup/workorder_templates'));
            File::copyDirectory(base_path('custom/templates/purchaseorder_templates'), base_path('custom/templates/V6Backup/purchaseorder_templates'));
        }
    }

    /**
     * replaces the copied V7customtemplate file variable
     * ($quote, $workorder, $invoice, $purchaseorder) with $document
     * path custom/templates/
     * quote_templates, workorder_templates, invoice_templates, purchaseorder_templates
     * @return void
     */
    public static function update(): void
    {
        $customV7QuoteTemplates = Directory::listAssocContents(base_path('custom/templates/quote_templates'));
        unset($customV7QuoteTemplates['custom.blade.php']);

        foreach ($customV7QuoteTemplates as $key => $value) {
            $content = file_get_contents(base_path('custom/templates/quote_templates/' . $value));
            $content = preg_replace('/\$quote/', '$document', $content);
            $content = preg_replace('/formatted_expires_at/', 'formatted_action_date', $content);
            file_put_contents(base_path('custom/templates/quote_templates/' . $value), $content);
        }

        $customV7WorkorderTemplates = Directory::listAssocContents(base_path('custom/templates/workorder_templates'));
        unset($customV7WorkorderTemplates['custom.blade.php']);

        foreach ($customV7WorkorderTemplates as $key => $value) {
            $content = file_get_contents(base_path('custom/templates/workorder_templates/' . $value));
            $content = preg_replace('/\$workorder/', '$document', $content);
            $content = preg_replace('/formatted_expires_at/', 'formatted_action_date', $content);
            $content = preg_replace('/formatted_workorder_date/', 'formatted_document_date', $content);
            file_put_contents(base_path('custom/templates/workorder_templates/' . $value), $content);
        }

        $customV7InvoiceTemplates = Directory::listAssocContents(base_path('custom/templates/invoice_templates'));
        unset($customV7InvoiceTemplates['custom.blade.php']);

        foreach ($customV7InvoiceTemplates as $key => $value) {
            $content = file_get_contents(base_path('custom/templates/invoice_templates/' . $value));
            $content = preg_replace('/\$invoice/', '$document', $content);
            $content = preg_replace('/formatted_due_at/', 'formatted_action_date', $content);
            file_put_contents(base_path('custom/templates/invoice_templates/' . $value), $content);
        }

        $customV7PurchaseorderTemplates = Directory::listAssocContents(base_path('custom/templates/purchaseorder_templates'));
        unset($customV7PurchaseorderTemplates['custom.blade.php']);

        foreach ($customV7PurchaseorderTemplates as $key => $value) {
            $content = file_get_contents(base_path('custom/templates/purchaseorder_templates/' . $value));
            $content = preg_replace('/\$purchaseorder/', '$document', $content);
            $content = preg_replace('/formatted_due_at/', 'formatted_action_date', $content);
            file_put_contents(base_path('custom/templates/purchaseorder_templates/' . $value), $content);
        }
    }

}
