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

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

//use Laravel\Sanctum\HasApiTokens;

//class User extends Model implements AuthenticatableContract, CanResetPasswordContract
class User extends Authenticatable
{
//    use Authenticatable, CanResetPassword, SoftDeletes, SoftCascadeTrait, HasRoles;
    use  HasFactory, Notifiable, HasRoles, SoftDeletes, SoftCascadeTrait;

    protected $softCascade = ['custom'];

    protected $casts = ['deleted_at' => 'datetime'];

    protected $table = 'users';

    protected $guarded = ['id', 'password', 'password_confirmation'];

    protected $hidden = ['password', 'remember_token', 'api_public_key', 'api_secret_key'];

    protected $appends = ['user_type', 'user_role'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client()
    {
        return $this->belongsTo('BT\Modules\Clients\Models\Client');
    }

    public function custom()
    {
        return $this->hasOne('BT\Modules\CustomFields\Models\UserCustom');
    }

    public function expenses()
    {
        return $this->hasMany('BT\Modules\Expenses\Models\Expense');
    }

    public function invoices()
    {
        return $this->hasMany('BT\Modules\Invoices\Models\Invoice');
    }

    public function quotes()
    {
        return $this->hasMany('BT\Modules\Quotes\Models\Quote');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getUserTypeAttribute()
    {
//        return ($this->client_id) ? 'client' : 'admin';
        return $this->roles()->first()->name;
    }

    public function getUserRoleAttribute()
    {
//        $roles = $this->roles()->get();
//        $role_names = [];
//        foreach ($roles as $role) {
//            $role_names[] = $role->name;
//        }
//        return implode(', ', $role_names);
        return $this->roles()->first()->name;

    }

    /*
    |--------------------------------------------------------------------------
    | Mutators
    |--------------------------------------------------------------------------
    */

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
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
