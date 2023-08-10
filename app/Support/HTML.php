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

class HTML
{
    public static function document($document)
    {
        if ($document->module_type == 'Purchaseorder') {
            app()->setLocale($document->vendor->language);
        } else {
            app()->setLocale($document->client->language);
        }

        config(['bt.baseCurrency' => $document->currency_code]);

        $template = str_replace('.blade.php', '', $document->template);

        if (view()->exists(strtolower($document->module_type).'_templates.'.$template)) {
            $template = strtolower($document->module_type).'_templates.'.$template;
        } else {
            $template = 'templates.'.strtolower($document->module_type).'s.default';
        }

        try {
            return view($template)
                ->with('document', $document)
                ->with('logo', $document->companyProfile->logo())->render();
        } catch (\Exception $e) {
            $msg = __('bt.custom_template_error');
            $msg .= $e->getMessage();

            return $msg;
        }
    }
}
