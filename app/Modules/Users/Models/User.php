<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Users\Models;

use Askedio\SoftCascade\Traits\SoftCascadeTrait;

use BT\Modules\Clients\Models\Client;
use BT\Modules\CustomFields\Models\UserCustom;
use BT\Modules\Documents\Models\Document;
use BT\Modules\Documents\Models\Invoice;
use BT\Modules\Documents\Models\Purchaseorder;
use BT\Modules\Documents\Models\Quote;
use BT\Modules\Documents\Models\Workorder;
use BT\Modules\Expenses\Models\Expense;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use  HasFactory, Notifiable, HasRoles, SoftDeletes, SoftCascadeTrait;

    protected $softCascade = ['custom'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $table = 'users';

    protected $guarded = ['id', 'password', 'password_confirmation'];

    protected $hidden = ['password', 'remember_token', 'api_public_key', 'api_secret_key'];

//    protected $appends = ['user_type', 'user_role'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function custom(): HasOne
    {
        return $this->hasOne(UserCustom::class);
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

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function workorders(): HasMany
    {
        return $this->hasMany(Workorder::class);
    }

    public function purchaseorders(): HasMany
    {
        return $this->hasMany(Purchaseorder::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */
    public function userType(): Attribute
    {
        return new Attribute(get: fn() => $this->roles()->first()->name);
    }

    public function userRole(): Attribute
    {
        return new Attribute(get: fn() => $this->roles()->first()->name);
    }

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    public function password(): Attribute
    {
        return new Attribute(set: fn($password) => $this->attributes['password'] = Hash::make($password));
    }
    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeUserType($query, $userType)
    {
        if ($userType == 'client') {
            $query->where('client_id', '<>', 0);
        } elseif ($userType == 'admin') {
            $query->where('client_id', 0);
        }

        return $query;
    }
}
