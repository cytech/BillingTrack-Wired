<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\CustomFields\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoiceCustom extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The table name
     * @var string
     */
    protected $table = 'recurring_invoices_custom';

    /**
     * The primary key
     * @var string
     */
    protected $primaryKey = 'recurring_invoice_id';

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = [];
}
