<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support\SixtoSeven\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Employees\Models\Employee;
use BT\Modules\Products\Models\Product;
use BT\Modules\TaxRates\Models\TaxRate;
use BT\Support\CurrencyFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecurringInvoiceItem extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $connection = 'mysql'; // necessary for livewire error:Queueing collections with multiple model connections is not supported    protected $connection = 'mysql'; // necessary for livewire error:Queueing collections with multiple model connections is not supported

    protected $softCascade = ['amount'];

    protected $casts = ['deleted_at' => 'datetime'];
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function amount(): HasOne
    {
        return $this->hasOne(RecurringInvoiceItemAmount::class, 'item_id');
    }

    public function recurringInvoice(): BelongsTo
    {
        return $this->belongsTo(RecurringInvoice::class);
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function taxRate2(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_2_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'resource_id')
            ->where('resource_table', '=', 'products');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'resource_id')
            ->where('resource_table', '=', 'employees');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedQuantity(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->quantity));
    }

    public function formattedNumericPrice(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->price));
    }

    public function formattedPrice(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->price, $this->recurringInvoice->currency));
    }

    public function formattedDescription(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->description));
    }
}
