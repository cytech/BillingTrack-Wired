<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support\Statuses;

class ExpenseStatuses extends AbstractStatuses
{
    protected static $statuses = [
        '0' => 'all_statuses',
        '1' => 'billed',
        '2' => 'not_billed',
        '3' => 'not_billable',
    ];
}
