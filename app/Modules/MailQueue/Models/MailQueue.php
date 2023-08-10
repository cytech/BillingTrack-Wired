<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\MailQueue\Models;

use BT\Support\DateFormatter;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MailQueue extends Model
{
    use SoftDeletes;

    protected $table = 'mail_queue';

    protected $guarded = [];

    //    protected $appends = ['formatted_created_at', 'formatted_from', 'formatted_to', 'formatted_cc', 'formatted_bcc', 'formatted_sent'];

    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function mailable(): MorphTo
    {
        return $this->morphTo();
    }

    //accessors
    public function formattedCreatedAt(): Attribute
    {
        return new Attribute(get: fn () => DateFormatter::format($this->created_at, true));
    }

    public function formattedFrom(): Attribute
    {
        $from = json_decode($this->from);

        return new Attribute(get: fn () => $from->email);
    }

    public function formattedTo(): Attribute
    {
        return new Attribute(get: fn () => implode(', ', json_decode($this->to)));
    }

    public function formattedCc(): Attribute
    {
        return new Attribute(get: fn () => implode(', ', json_decode($this->cc)));
    }

    public function formattedBcc(): Attribute
    {
        return new Attribute(get: fn () => implode(', ', json_decode($this->bcc)));
    }

    public function formattedSent(): Attribute
    {
        return new Attribute(get: fn () => ($this->sent) ? trans('bt.yes') : trans('bt.no'));
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeKeywords($query, $keywords = null)
    {
        if ($keywords) {
            $keywords = strtolower($keywords);

            $query->where('created_at', 'like', '%'.$keywords.'%')
                ->orWhere('from', 'like', '%'.$keywords.'%')
                ->orWhere('to', 'like', '%'.$keywords.'%')
                ->orWhere('cc', 'like', '%'.$keywords.'%')
                ->orWhere('bcc', 'like', '%'.$keywords.'%')
                ->orWhere('subject', 'like', '%'.$keywords.'%');
        }

        return $query;
    }
}
