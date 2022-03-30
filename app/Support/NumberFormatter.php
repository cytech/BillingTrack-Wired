<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Support;

class NumberFormatter
{
    /**
     * Formats a number accordingly.
     *
     * @param  float $number
     * @param  object $currency
     * @param  integer $decimalPlaces
     * @return float
     */
    public static function format($number, $currency = null, $decimalPlaces = null)
    {
        $currency      = ($currency) ?: config('bt.currency');
        $decimalPlaces = ($decimalPlaces) ?: config('bt.amountDecimals');

        return number_format($number, $decimalPlaces, $currency->decimal, $currency->thousands);
    }

    /**
     * Unformats a formatted number.
     *
     * @param  float $number
     * @param  object $currency
     * @return float
     */
    public static function unformat($number, $currency = null)
    {
        $currency = ($currency) ?: config('bt.currency');

        $number = str_replace($currency->decimal, 'D', $number);
        $number = str_replace($currency->thousands, '', $number);
        $number = str_replace('D', '.', $number);

        return $number;
    }
}
