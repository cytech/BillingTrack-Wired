<?php

namespace BT\Widgets\Dashboard\ClientActivity\Composers;

use BT\Modules\Activity\Models\Activity;

class ClientActivityWidgetComposer
{
    public function compose($view)
    {
        $recentClientActivity = Activity::where('activity', 'like', 'public%')
            ->orderBy('created_at', 'DESC')
            ->take(10)
            ->get()->groupBy(function ($item) {
                return $item->created_at->format('Y-m-d');
            });

        $view->with('recentClientActivity', $recentClientActivity);
    }
}
