<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\Groups\Models;

use BT\Modules\Settings\Models\Setting;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{

    protected $guarded = ['id'];


    /*
    |--------------------------------------------------------------------------
    | Static Methods
    |--------------------------------------------------------------------------
    */

    public static function findIdByName($name)
    {
        if ($group = self::where('name', $name)->first())
        {
            return $group->id;
        }

        return null;
    }

    public static function generateNumber($id)
    {
        $group = self::find($id);

        // Only check for resets if this group has been used.
        if ($group->last_id <> 0)
        {
            // Check for yearly reset.
            if ($group->reset_number == 1)
            {
                if ($group->last_year <> date('Y'))
                {
                    $group->next_id = 1;
                    $group->save();
                }
            }
            // Check for monthly reset.
            elseif ($group->reset_number == 2)
            {
                if ($group->last_month <> date('m') or $group->last_year <> date('Y'))
                {
                    $group->next_id = 1;
                    $group->save();
                }
            }
            // Check for weekly reset.
            elseif ($group->reset_number == 3)
            {
                if ($group->last_week <> date('W') or $group->last_month <> date('m') or $group->last_year <> date('Y'))
                {
                    $group->next_id = 1;
                    $group->save();
                }
            }
        }

        $number = $group->format;

        $number = str_replace('{NUMBER}', str_pad($group->next_id, $group->left_pad, '0', STR_PAD_LEFT), $number);
        $number = str_replace('{YEAR}', date('Y'), $number);
        $number = str_replace('{MONTH}', date('m'), $number);
        $number = str_replace('{WEEK}', date('W'), $number);
        $number = str_replace('{MONTHSHORTNAME}', date('M'), $number);

        $group->last_id    = $group->next_id;
        $group->last_week  = date('W');
        $group->last_month = date('m');
        $group->last_year  = date('Y');
        $group->save();

        return $number;
    }

    public static function getList()
    {
        return self::orderBy('name')->pluck('name', 'id')->all();
    }

    public static function getDefaultList()
    {
        $default_groups = ['invoiceGroup', 'quoteGroup', 'workorderGroup', 'purchaseorderGroup', 'recurringinvoiceGroup'];
        $default_list = [];
        foreach ($default_groups as $default) {
            $default_list[] = Setting::getByKey($default);
        }
        return $default_list;
    }

    public static function incrementNextId($document)
    {
        $group              = self::find($document->group_id);
        $group->next_id     = $group->next_id + 1;
        $group->last_number = $document->number;
        $group->save();
    }
}
