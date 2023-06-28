<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Vendors\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\CustomFields\Models\VendorCustom;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Notes\Models\Note;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\PaymentTerms\Models\PaymentTerm;
use BT\Modules\Users\Models\User;
use BT\Support\CurrencyFormatter;
use BT\Support\Statuses\DocumentStatuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use SoftDeletes;
    use SoftCascadeTrait;

    protected $softCascade = ['contacts', 'custom', 'purchaseorders', 'attachments', 'notes'];

    protected $table = 'vendors';

    protected $casts = ['deleted_at' => 'datetime'];

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function firstOrCreateByName($id, $name)
    {
        $vendor = self::firstOrNew([
            'id' => $id,
        ]);

        if (!$vendor->id) {
            $vendor->name = $name;
            $vendor->save();
            return self::find($vendor->id);
        }

        return $vendor;
    }

    public static function inUse($id)
    {
        if (Purchaseorder::where('client_id', $id)->count()) {
            return true;
        }

        if (Expense::where('vendor_id', $id)->count()) {
            return true;
        }

        return false;
    }


    /*
   |--------------------------------------------------------------------------
   | Relationships
   |--------------------------------------------------------------------------
   */

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function custom(): HasOne
    {
        return $this->hasOne(VendorCustom::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function paymentterm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    public function purchaseorders(): HasMany
    {
        return $this->hasMany(Purchaseorder::class, 'client_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function attachmentPath(): Attribute
    {
        return new Attribute(get: fn() => attachment_path('vendors/' . $this->id));
    }

    public function attachmentPermissionOptions(): Attribute
    {
        return new Attribute(get: fn() => ['0' => trans('bt.not_visible')]);
    }

    public function formattedBalance(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->balance, $this->currency));
    }

    public function formattedPaid(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->paid, $this->currency));
    }

    public function formattedTotal(): Attribute
    {
        return new Attribute(get: fn() => CurrencyFormatter::format($this->total, $this->currency));
    }

    public function formattedAddress(): Attribute
    {
        return new Attribute(get: fn() => nl2br(formatAddress($this)));
    }

    public function formattedAddress2(): Attribute
    {
        return new Attribute(get: fn() => nl2br(formatAddress2($this)));
    }

    public function vendorEmail(): Attribute
    {
        return new Attribute(get: fn() => $this->email);
    }

    public function vendorTerms(): Attribute
    {
        if ($this->paymentterm->id != 1) {
            return new Attribute(get: fn() => $this->paymentterm->num_days);
        } else
            return new Attribute(get: fn() => config('bt.purchaseordersDueAfter'));
    }

    public function formattedActive(): Attribute
    {
        return new Attribute(get: fn() => $this->active ? trans('bt.yes') : trans('bt.no'));
    }

    /*
        |--------------------------------------------------------------------------
        | Scopes
        |--------------------------------------------------------------------------
        */

    public function scopeGetSelect()
    {
        return self::select('vendors.*',
            DB::raw('(' . $this->getBalanceSql() . ') as balance'),
            DB::raw('(' . $this->getPaidSql() . ') AS paid'),
            DB::raw('(' . $this->getTotalSql() . ') AS total')
        );
    }

    public function scopeStatus($query, $status)
    {
        if ($status == 'active') {
            $query->where('active', 1);
        } elseif ($status == 'inactive') {
            $query->where('active', 0);
        }

        return $query;
    }

    public function scopeKeywords($query, $keywords)
    {
        if ($keywords) {
            $keywords = explode(' ', $keywords);

            foreach ($keywords as $keyword) {
                if ($keyword) {
                    $keyword = strtolower($keyword);

                    $query->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name),LOWER(email),phone,fax,mobile)"), 'LIKE', "%$keyword%");
                }
            }
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | Subqueries
    |--------------------------------------------------------------------------
    */

    private function getBalanceSql()
    {
        return DB::table('document_amounts')->select(DB::raw('sum(balance)'))->whereIn('document_id', function ($q) {
            $q->select('id')
                ->from('documents')
                ->where('documents.client_id', '=', DB::raw(DB::getTablePrefix() . 'vendors.id'))
                ->where('documents.document_status_id', '<>', DB::raw(DocumentStatuses::getStatusId('canceled')))
                ->whereNull('deleted_at');
        })->toSql();
    }

    private function getPaidSql()
    {
        return DB::table('document_amounts')->select(DB::raw('sum(paid)'))->whereIn('document_id', function ($q) {
            $q->select('id')->from('documents')
                ->where('documents.client_id', '=', DB::raw(DB::getTablePrefix() . 'vendors.id'))
                ->where('documents.document_type', '=', DB::raw('"' . addslashes(Purchaseorder::class) . '"'));;
        })->toSql();
    }

    private function getTotalSql()
    {
        return DB::table('document_amounts')->select(DB::raw('sum(total)'))->whereIn('document_id', function ($q) {
            $q->select('id')->from('documents')
                ->where('documents.client_id', '=', DB::raw(DB::getTablePrefix() . 'vendors.id'))
                ->where('documents.document_type', '=', DB::raw('"' . addslashes(Purchaseorder::class) . '"'));
        })->toSql();
    }

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function getList()
    {
        return self::whereIn('id', function ($query) {
            $query->select('vendor_id')->distinct()->from('expenses');
        })->orderBy('name')
            ->pluck('name', 'id')
            ->all();
    }
}
