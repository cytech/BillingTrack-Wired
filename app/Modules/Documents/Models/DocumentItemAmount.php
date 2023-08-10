<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Documents\Models;

use BT\Support\CurrencyFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DocumentItemAmount extends Model
{
    use SoftDeletes;

    protected $casts = ['deleted_at' => 'datetime'];

    /**
     * Guarded properties
     *
     * @var array
     */
    protected $guarded = ['id'];

    public function item(): BelongsTo
    {
        return $this->belongsTo(DocumentItem::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedSubtotal(): Attribute
    {
        return new Attribute(get: fn () => CurrencyFormatter::format($this->subtotal, $this->item->document->currency));
    }

    public function formattedTax(): Attribute
    {
        return new Attribute(get: fn () => CurrencyFormatter::format($this->tax, $this->item->document->currency));
    }

    public function formattedTotal(): Attribute
    {
        return new Attribute(get: fn () => CurrencyFormatter::format($this->total, $this->item->document->currency));
    }
}
