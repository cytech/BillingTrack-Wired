<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Expenses\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Categories\Models\Category;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\CustomFields\Models\ExpenseCustom;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Vendors\Models\Vendor;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $softCascade = ['attachments', 'custom'];

    protected $table = 'expenses';

    protected $guarded = ['id'];

    protected $casts = ['deleted_at' => 'datetime'];

//    protected $appends = ['formatted_description', 'formatted_expense_date', 'formatted_amount', 'is_billable', 'has_been_billed'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function custom(): HasOne
    {
        return $this->hasOne(ExpenseCustom::class);
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(Invoice::class, 'id', 'invoice_id')->withTrashed();
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function attachmentPath(): Attribute
    {
        return new Attribute(get: fn() => attachment_path('expenses/' . $this->id));
    }

    public function attachmentPermissionOptions(): Attribute
    {
        return new Attribute(get: fn() => [
            '0' => trans('bt.not_visible'),
            '1' => trans('bt.visible'),
        ]);
    }

    public function formattedAmount(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->amount));
    }

    public function formattedTax(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->tax));
    }

    public function formattedDescription(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->description));
    }

    public function formattedExpenseDate(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->expense_date));
    }

    public function formattedNumericAmount(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->amount));
    }

    public function formattedNumericTax(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->tax));
    }

    public function hasBeenBilled(): Attribute
    {
        if (!is_null($this->invoice_id)) {
            return new Attribute(get: fn() => true);
        }
        return new Attribute(get: fn() => false);
    }

    public function isBillable(): Attribute
    {
        if ($this->client_id) {
            return new Attribute(get: fn() => true);
        }
        return new Attribute(get: fn() => false);
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeCategoryId($query, $categoryId = null)
    {
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query;
    }

    public function scopeCompanyProfileId($query, $companyProfileId = null)
    {
        if ($companyProfileId) {
            $query->where('company_profile_id', $companyProfileId);
        }

        return $query;
    }

    public function scopeDefaultQuery($query)
    {
        return $query->select('expenses.*', 'categories.name AS category_name',
            'vendors.name AS vendor_name', 'clients.name AS client_name', 'clients.unique_name AS unique_name')
            ->join('categories', 'categories.id', '=', 'expenses.category_id')
            ->leftJoin('vendors', 'vendors.id', '=', 'expenses.vendor_id')
            ->leftJoin('clients', 'clients.id', '=', 'expenses.client_id');
    }

    public function scopeKeywords($query, $keywords = null)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where('expenses.expense_date', 'like', '%' . $keywords . '%')
                ->orWhere('expenses.description', 'like', '%' . $keywords . '%')
                ->orWhere('vendors.name', 'like', '%' . $keywords . '%')
                ->orWhere('clients.name', 'like', '%' . $keywords . '%')
                ->orWhere('categories.name', 'like', '%' . $keywords . '%');
        }

        return $query;
    }

    public function scopeStatus($query, $status = null)
    {
        if ($status) {
            switch ($status) {
                case 'billed':
                    $query->where('invoice_id', '<>', 0);
                    break;
                case 'not_billed':
                    $query->where('client_id', '<>', 0)->where('invoice_id', '=', 0)->orWhere('invoice_id', '=', null);
                    break;
                case 'not_billable':
                    $query->where('client_id', 0);
                    break;
            }
        }

        return $query;
    }

    public function scopeVendorId($query, $vendorId = null)
    {
        if ($vendorId) {
            $query->where('vendor_id', $vendorId);
        }

        return $query;
    }

    public function scopeDateRange($query, $fromDate, $toDate)
    {
        return $query->where('expense_date', '>=', $fromDate)
            ->where('expense_date', '<=', $toDate);
    }
}
