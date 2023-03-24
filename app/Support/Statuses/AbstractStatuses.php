<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support\Statuses;

abstract class AbstractStatuses
{
    public static function statuses()
    {
        return static::$statuses;
    }

    /**
     * Returns an array of statuses to populate dropdown list.
     * removes 1st item (all_statuses)
     * @return array
     */
    public static function lists()
    {
        $statuses = static::$statuses;

        unset($statuses[0]);

        foreach ($statuses as $key => $status)
        {
            $statuses[$key] = trans('bt.' . $status);
        }

        return $statuses;
    }

    public static function listsType($module_type)
    {
        $statuses = static::$statuses;

        unset($statuses[0]);

        foreach ($statuses as $key => $status)
        {
            $statuses[$key] = trans('bt.' . $status);
        }

        switch ($module_type){
            case 1:
            case 2:
                unset($statuses[6]);
                break;
            case 3:
                unset($statuses[3]);
                unset($statuses[4]);
                break;
        }

        return $statuses;
    }

    /**
     * Returns an array of statuses to populate dropdown list.
     * does not remove 1st item (all_statuses)
     * @return array
     */
    public static function listsAll()
    {
        $statuses = static::$statuses;


        foreach ($statuses as $key => $status)
        {
            $statuses[$key] = trans('bt.' . $status);
        }

        return $statuses;
    }

    public static function listsAllFlat()
    {
        $statuses = [];

        foreach (static::$statuses as $status)
        {
            $statuses[$status] = trans('bt.' . $status);
        }

        return $statuses;
    }

    public static function listsAllFlatDT($module_type)
    {
        $statuses = [];

        foreach (static::$statuses as $key => $status)
            if ($key === array_key_first(static::$statuses)) {
                $statuses[''] = trans('bt.' . $status);
            } else {
                $statuses[$status] = trans('bt.' . $status);
            }

        if ($module_type === 'Invoice' || $module_type === 'Purchaseorder') {
            $statuses['overdue'] = __('bt.overdue');
        }

        return $statuses;
    }

    /**
     * Returns the status key.
     *
     * @param  string $value
     * @return integer
     */
    public static function getStatusId($value)
    {
        return array_search($value, static::$statuses);
    }
}
