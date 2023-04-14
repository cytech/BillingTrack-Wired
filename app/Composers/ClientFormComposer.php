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
            ->with('documentTemplates', DocumentTemplates::lists())
            ->with('languages', Languages::listLanguages());
    }
}
