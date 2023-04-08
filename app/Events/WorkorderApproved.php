<?php

namespace BT\Events;

use BT\Modules\Documents\Models\Workorder;

use Illuminate\Queue\SerializesModels;

class WorkorderApproved extends Event
{
    use SerializesModels;

    public function __construct(Workorder $workorder)
    {
        $this->workorder = $workorder;
    }
}
