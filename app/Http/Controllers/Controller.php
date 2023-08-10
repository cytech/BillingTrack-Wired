<?php

namespace BT\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * check the specified permission - abort to 404 if not granted
     **/
    protected function check_permission($perm = '')
    {

        if (! auth()->user()->can($perm)) {
            return abort(403, 'Unauthorized');
        }

        return true;

    } // check_permission
}
