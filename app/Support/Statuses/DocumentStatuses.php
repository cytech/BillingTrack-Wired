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

class DocumentStatuses extends AbstractStatuses
{
    protected static $statuses = [
        '0' => 'all_statuses',
        '1' => 'draft',
        '2' => 'sent',
        '3' => 'approved', //not allowed invoice, po
        '4' => 'rejected', //not allowed invoice, po
        '5' => 'canceled', //po was 6
        '6' => 'paid', //invoice and  po was 5
        '7' => 'received', //po was 3
        '8' => 'partial', //po was 4
    ];
}
