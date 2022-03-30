<?php

namespace BT\Composers;

use BT\Modules\Currencies\Models\Currency;
use BT\Support\Languages;

class VendorFormComposer
{
    public function compose($view)
    {
        $view->with('currencies', Currency::getList())
            ->with('languages', Languages::listLanguages());
    }
}
