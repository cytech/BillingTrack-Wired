<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Clients\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Attachments\Models\Attachment;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\CustomFields\Models\ClientCustom;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Industries\Models\Industry;
use BT\Modules\Merchant\Models\MerchantClient;
use BT\Modules\Notes\Models\Note;
use BT\Modules\Payments\Models\Payment;
use BT\Modules\PaymentTerms\Models\PaymentTerm;
use BT\Modules\RecurringInvoices\Models\RecurringInvoice;
use BT\Modules\Sizes\Models\Size;
use BT\Modules\TimeTracking\Models\TimeTrackingProject;
use BT\Modules\Users\Models\User;
use BT\Support\CurrencyFormatter;
use BT\Support\DateFormatter;
use BT\Support\Statuses\DocumentStatuses;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Client extends Model
{

    use SoftDeletes;
    use SoftCascadeTrait;

    protected $softCascade = ['contacts', 'custom', 'invoices', 'workorders', 'quotes', 'projects', 'recurringInvoices',
        'merchant', 'attachments', 'notes'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $guarded = ['id', 'password'];

    protected $hidden = ['password', 'remember_token'];

//    protected $appends = ['formatted_balance', 'formatted_createdat'];

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    /*public static function firstOrCreateByUniqueName($uniqueName)
    {
        $client = self::firstOrNew([
            'unique_name' => $uniqueName,
        ]);

        if (!$client->id)
        {
            $client->name = $uniqueName;
            $client->unique_name = substr($uniqueName, 0, 10) . '_' .
                substr(base_convert(mt_rand(),10,36),0,5);
            $client->save();
            return self::find($client->id);
        }

        return $client;
    }*/

    public static function firstOrCreateByName($id, $name)
    {
        $client = self::firstOrNew([
            'id' => $id,
        ]);

        if (!$client->id) {
            $client->name = $name;
            $client->unique_name = self::generateUniqueName($name);
            $client->save();
            return self::find($client->id);
        }

        return $client;
    }

    public static function generateUniqueName($name)
    {
        return substr($name, 0, 10) . '_' .
            substr(base_convert(mt_rand(), 10, 36), 0, 5);
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
        return $this->hasOne(ClientCustom::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function merchant(): HasOne
    {
        return $this->hasOne(MerchantClient::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /*public function payments()
    {
        return $this->hasManyThrough('BT\Modules\Payments\Models\Payment', 'BT\Modules\Invoices\Models\Invoice');
    }*/

    public function projects(): HasMany
    {
        return $this->hasMany(TimeTrackingProject::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function workorders(): HasMany
    {
        return $this->hasMany(Workorder::class);
    }

    public function recurringInvoices(): HasMany
    {
        return $this->hasMany(RecurringInvoice::class);
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function size(): BelongsTo
    {
        return $this->belongsTo(Size::class);
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(Industry::class);
    }

    public function paymentterm(): BelongsTo
    {
        return $this->belongsTo(PaymentTerm::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedCreatedat(): Attribute
    {
        return new Attribute(get: fn() => DateFormatter::format($this->attributes['created_at']));
    }

    public function uniqueNamePrefix(): Attribute
    {
        return new Attribute(get: fn() => substr($this->attributes['unique_name'], 0, strpos($this->attributes['unique_name'], "_") + 1));
    }

    public function uniqueNameSuffix(): Attribute
    {
        return new Attribute(get: fn() => substr($this->attributes['unique_name'], strpos($this->attributes['unique_name'], "_") + 1));
    }

    public function attachmentPath(): Attribute
    {
        return new Attribute(get: fn() => attachment_path('clients/' . $this->id));
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

    public function clientEmail(): Attribute
    {
        return new Attribute(get: fn() => $this->email);
    }

    public function clientTerms(): Attribute
    {
        if ($this->paymentterm->id != 1) {
            return new Attribute(get: fn() => $this->paymentterm->num_days);
        } else {
            return new Attribute(get: fn() => config('bt.invoicesDueAfter'));
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeGetSelect()
    {
        return self::select('clients.*',
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
        } elseif ($status == 'company') {
            $query->where('is_company', 1);
        } elseif ($status == 'individual') {
            $query->where('is_company', 0);
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
                ->where('documents.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'))
                ->where('documents.document_status_id', '<>', DB::raw(DocumentStatuses::getStatusId('canceled')))
                ->whereNull('deleted_at');
        })->toSql();
    }

    private function getPaidSql()
    {
        return DB::table('document_amounts')->select(DB::raw('sum(paid)'))->whereIn('document_id', function ($q) {
            $q->select('id')->from('documents')->where('documents.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'));
        })->toSql();
    }

    private function getTotalSql()
    {
        //restrict total (billed) to invoices
        return DB::table('document_amounts')->select(DB::raw('sum(total)'))->whereIn('document_id', function ($q) {
            $q->select('id')->from('documents')
                ->where('documents.client_id', '=', DB::raw(DB::getTablePrefix() . 'clients.id'))
                ->where('documents.document_type', '=', DB::raw('"' . addslashes(Invoice::class) . '"'));
        })->toSql();
    }
}
