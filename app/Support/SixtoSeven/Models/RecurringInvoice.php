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
use BT\Modules\Activity\Models\Activity;
use BT\Modules\Clients\Models\Client;
use BT\Modules\CompanyProfiles\Models\CompanyProfile;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\CustomFields\Models\RecurringinvoiceCustom;
use BT\Modules\Groups\Models\Group;
use BT\Modules\Users\Models\User;
use BT\Support\DateFormatter;
use BT\Support\NumberFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class RecurringInvoice extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $softCascade = ['recurringInvoiceItems', 'custom', 'amount', 'activities'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $guarded = ['id'];

//    protected $appends = ['formatted_next_date', 'formatted_stop_date', 'formatted_summary'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function activities(): MorphMany
    {
        return $this->morphMany(Activity::class, 'audit');
    }

    public function amount(): HasOne
    {
        return $this->hasOne(RecurringInvoiceAmount::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function companyProfile(): BelongsTo
    {
        return $this->belongsTo(CompanyProfile::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function custom(): HasOne
    {
        return $this->hasOne(RecurringinvoiceCustom::class, 'recurringinvoice_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(RecurringInvoiceItem::class)
            ->orderBy('display_order');
    }

    // This and items() are the exact same. This is added to appease the IDE gods
    // and the fact that Laravel has a protected items property.
    public function recurringInvoiceItems(): HasMany
    {
        return $this->hasMany(RecurringInvoiceItem::class)
            ->orderBy('display_order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedFooter(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->footer));
    }

    public function formattedNextDate(): Attribute
    {
        if ($this->next_date <> '0000-00-00') {
            return new Attribute(get: fn() => DateFormatter::format($this->next_date));
        }
        return new Attribute(get: fn() => '');
    }

    public function formattedNumericDiscount(): Attribute
    {
        return new Attribute(get: fn() => NumberFormatter::format($this->discount));
    }

    public function formattedStopDate(): Attribute
    {
        if ($this->stop_date <> '0000-00-00') {
            return new Attribute(get: fn() => DateFormatter::format($this->stop_date));
        }
        return new Attribute(get: fn() => '');
    }

    public function formattedTerms(): Attribute
    {
        return new Attribute(get: fn() => nl2br($this->terms));
    }

//    public function isForeignCurrency(): Attribute
//    {
//        if ($this->currency_code == config('bt.baseCurrency'))
//        {
//            return new Attribute(get: fn() => False);
//        }
//        return new Attribute(get: fn() => True);
//    }

    public function formattedSummary(): Attribute
    {
        return new Attribute(get: fn() => mb_strimwidth((string)$this->summary, 0, 50, '...'));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeActive($query)
    {
        return $query->where('stop_date', '0000-00-00')
            ->orWhere('stop_date', '>', date('Y-m-d'));
    }

    public function scopeClientId($query, $clientId = null)
    {
        if ($clientId) {
            $query->where('client_id', $clientId);
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

    public function scopeInactive($query)
    {
        return $query->where('stop_date', '<>', '0000-00-00')
            ->where('stop_date', '<=', date('Y-m-d'));
    }

    public function scopeKeywords($query, $keywords = null)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where('summary', 'like', '%' . $keywords . '%')
                ->orWhereIn('client_id', function ($query) use ($keywords) {
                    $query->select('id')->from('clients')->where(DB::raw("CONCAT_WS('^',LOWER(name),LOWER(unique_name))"), 'like', '%' . $keywords . '%');
                });
        }

        return $query;
    }

    public function scopeRecurNow($query)
    {
        $query->where('next_date', '<>', '0000-00-00');
        $query->where('next_date', '<=', date('Y-m-d'));
        $query->where(function ($q) {
            $q->where('stop_date', '0000-00-00');
            $q->orWhere('next_date', '<=', DB::raw('stop_date'));
        });

        return $query;
    }

    public function scopeStatus($query, $status)
    {
        return match ($status) {
            'active' => $query->active(),
            'inactive' => $query->inactive(),
            default => $query,
        };

    }
}
