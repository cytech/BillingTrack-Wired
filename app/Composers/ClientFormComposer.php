<?php

namespace BT\Composers;

use BT\Modules\Currencies\Models\Currency;
use BT\Modules\Documents\Support\DocumentTemplates;
use BT\Support\Languages;

class ClientFormComposer
{
    public function compose($view)
    {
        $view->with('currencies', Currency::getList())
//            ->with('documentTemplates', DocumentTemplates::lists('Document'))
            ->with('invoiceTemplates', DocumentTemplates::lists('Invoice'))
            ->with('quoteTemplates', DocumentTemplates::lists('Quote'))
//            ->with('workorderTemplates', DocumentTemplates::lists('Workorder'))
//            ->with('purchaseorderTemplates', DocumentTemplates::lists('Purchaseorder'))
            ->with('languages', Languages::listLanguages());
    }
}
