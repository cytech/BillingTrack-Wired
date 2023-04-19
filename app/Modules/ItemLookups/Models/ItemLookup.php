<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\ItemLookups\Models;

use BT\Modules\Employees\Models\Employee;
use BT\Modules\Products\Models\Product;
use BT\Modules\TaxRates\Models\TaxRate;
use BT\Support\CurrencyFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ItemLookup extends Model
{

    /**
     * Guarded properties
     * @var array
     */
    protected $guarded = ['id'];

//    protected $appends = ['formatted_name', 'formatted_price'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class)->withDefault(['name' => '']);
    }

    public function taxRate2(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class, 'tax_rate_2_id')->withDefault(['name' => '']);
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

    public function formattedPrice(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->price));
    }

    public function formattedNumericPrice(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->price));
    }

    //format drivers blue
    public function formattedName(): Attribute
    {
        if ($this->resource_table == 'employees') {
            if (Employee::find($this->resource_id)->driver == 1) {
                return new Attribute(get: fn() => '<span style = "color:blue">' . $this->name . '</span>');
            }
            return new Attribute(get: fn() => $this->name);
        }
        return new Attribute(get: fn() => $this->name);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            $keywords = explode(' ', $keywords);

            foreach ($keywords as $keyword) {
                if ($keyword) {
                    $keyword = strtolower($keyword);

                    $query->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(description),price)"), 'LIKE', "%$keyword%");
                }
            }
        }
        return $query;
    }
}
