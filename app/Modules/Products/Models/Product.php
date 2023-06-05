<?php


/**
 * This file is part of BillingTrack.
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Products\Models;

use BT\Modules\Documents\Models\DocumentItem;
use BT\Modules\Documents\Models\Purchaseorder;
//use BT\Modules\RecurringInvoices\Models\RecurringInvoiceItem;
use BT\Modules\Categories\Models\Category;
use BT\Modules\TaxRates\Models\TaxRate;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\CurrencyFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

    protected $table = 'products';

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class)->withDefault(['name' => '']);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault(['name' => '']);
    }

    public function purchaseorders(): HasMany
    {
        return $this->hasMany(Purchaseorder::class);
    }

    public function inventorytype(): BelongsTo
    {
        return $this->belongsTo(InventoryType::class);
    }

    public function documentitem(): BelongsTo
    {
        return $this->belongsTo(DocumentItem::class, 'resource_id', 'id')
            ->where('resource_table', '=', 'products');
    }

    public function recurringinvoiceitem(): BelongsTo
    {
        return $this->belongsTo(DocumentItem::class, 'resource_id', 'id')
            ->where('resource_table', '=', 'products');
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class)->withDefault(['name' => '']);
    }

    public function taxRate2(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_2_id')->withDefault(['name' => '']);
    }

    public function isTrackable(): Attribute
    {
        return new Attribute(get: fn() => $this->inventorytype->tracked);
    }

    public function formattedPrice(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->price));
    }

    public function formattedCost(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->cost));
    }

    public function formattedNumericPrice(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->price));
    }

    public function formattedActive(): Attribute
    {
        return new Attribute(get: fn() => $this->active ? trans('bt.yes') : trans('bt.no'));
    }

    //inventory tracked scope
    public function scopeTracked($query)
    {
        return $query->whereIn('inventorytype_id', InventoryType::where('tracked', 1)->get('id'));
    }

    public function scopeStatus($query, $status)
    {
        if ($status == 'active') {
            $query->where($this->table . '.active', 1);
        } elseif ($status == 'inactive') {
            $query->where($this->table . '.active', 0);
        }

        return $query;
    }
}
