<?php

namespace BT\Widgets\Dashboard\TodaysWorkorders\Composers;

use Carbon\Carbon;
use BT\Modules\Documents\Models\Workorder;

class TodaysWorkordersWidgetComposer
{
    public function compose($view)
    {
        $today = new Carbon();

        $todaysWorkorders = Workorder::where( 'job_date', '=', $today->format('Y-m-d'))
            ->where('document_status_id', 3)->get();

        $view->with('todaysWorkorders', $todaysWorkorders);
    }
}
