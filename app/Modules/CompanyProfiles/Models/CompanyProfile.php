<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\CompanyProfiles\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;
use BT\Modules\Currencies\Models\Currency;
use BT\Modules\CustomFields\Models\CompanyProfileCustom;
use BT\Modules\Expenses\Models\Expense;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Quote;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyProfile extends Model
{
    use SoftDeletes;

    use SoftCascadeTrait;

    protected $softCascade = ['custom'];

    protected $guarded = ['id'];

    public static function getList()
    {
        return self::orderBy('company')->pluck('company', 'id')->all();
    }

    public static function inUse($id)
    {
        if (Invoice::where('company_profile_id', $id)->count()) {
            return true;
        }

        if (Quote::where('company_profile_id', $id)->count()) {
            return true;
        }

        if (Expense::where('company_profile_id', $id)->count()) {
            return true;
        }

        if (config('bt.defaultCompanyProfile') == $id) {
            return true;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function custom(): HasOne
    {
        return $this->hasOne(CompanyProfileCustom::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedAddress(): Attribute
    {
        return new Attribute(get: fn() => nl2br(formatAddress($this)));
    }

    public function formattedAddress2(): Attribute
    {
        return new Attribute(get: fn() => nl2br(formatAddress2($this)));
    }


    public function logoUrl(): Attribute
    {
        if ($this->logo) {
            return new Attribute(get: fn() => route('companyProfiles.logo', [$this->id]));
        }
        return new Attribute(get: fn() => null);
    }

    public function logo($width = null, $height = null)
    {
        if ($this->logo and file_exists(storage_path($this->logo))) {
            $logo = base64_encode(file_get_contents(storage_path($this->logo)));

            $style = '';

            if ($width and !$height) {
                $style = 'width: ' . $width . 'px;';
            } elseif ($width and $height) {
                $style = 'width: ' . $width . 'px; height: ' . $height . 'px;';
            }

            return '<img id="cp-logo" src="data:image/png;base64,' . $logo . '" style="' . $style . '">';
        }

        return null;
    }
}
