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

use BT\Modules\Notes\Models\Note;
use BT\Modules\Titles\Models\Title;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $casts = ['deleted_at' => 'datetime'];

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Title::class);
    }

    public function notes(): MorphMany
    {
        return $this->morphMany(Note::class, 'notable');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function formattedContact(): Attribute
    {
        return new Attribute(get: fn() => $this->name . ' <' . $this->email . '>');
    }

    public function formattedDefaultBcc(): Attribute
    {
        return new Attribute(get: fn() => ($this->default_bcc) ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedDefaultCc(): Attribute
    {
        return new Attribute(get: fn() => ($this->default_cc) ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedDefaultTo(): Attribute
    {
        return new Attribute(get: fn() => ($this->default_to) ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedIsPrimary(): Attribute
    {
        return new Attribute(get: fn() => ($this->is_primary) ? trans('bt.yes') : trans('bt.no'));
    }

    public function formattedOptin(): Attribute
    {
        return new Attribute(get: fn() => ($this->optin) ? trans('bt.yes') : trans('bt.no'));
    }
}
