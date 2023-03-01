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

use DateInterval;
use DateTime;

class DateFormatter
{
    /**
     * Returns an array of date format options.
     *
     * @return array
     */
    static function formats(): array
    {
        return [
            'm/d/Y' => [ //php (jquery datetimepicker)
                'setting'    => 'm/d/Y',
                'datepicker' => 'mm/dd/yyyy', //daterangepicker
                'datetimepicker' => 'm/d/Y' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'm-d-Y' => [
                'setting'    => 'm-d-Y',
                'datepicker' => 'mm-dd-yyyy',
                'datetimepicker' => 'm-d-Y' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'm.d.Y' => [
                'setting'    => 'm.d.Y',
                'datepicker' => 'mm.dd.yyyy',
                'datetimepicker' => 'm.d.Y' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'Y/m/d' => [
                'setting'    => 'Y/m/d',
                'datepicker' => 'yyyy/mm/dd',
                'datetimepicker' => 'Y/m/d' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'Y-m-d' => [
                'setting'    => 'Y-m-d',
                'datepicker' => 'yyyy-mm-dd',
                'datetimepicker' => 'Y-m-d' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'Y.m.d' => [
                'setting'    => 'Y.m.d',
                'datepicker' => 'yyyy.mm.dd',
                'datetimepicker' => 'Y.m.d' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'd/m/Y' => [
                'setting'    => 'd/m/Y',
                'datepicker' => 'dd/mm/yyyy',
                'datetimepicker' => 'd/m/Y' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'd-m-Y' => [
                'setting'    => 'd-m-Y',
                'datepicker' => 'dd-mm-yyyy',
                'datetimepicker' => 'd-m-Y' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
            'd.m.Y' => [
                'setting'    => 'd.m.Y',
                'datepicker' => 'dd.mm.yyyy',
                'datetimepicker' => 'd.m.Y' . (!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i'), //jquery datetimepicker
            ],
        ];
    }

    /**
     * Returns a flattened version of the format() method array to display as dropdown options.
     *
     * @return array
     */
    public static function dropdownArray(): array
    {
        $formats = self::formats();

        $return = [];

        foreach ($formats as $format)
        {
            $return[$format['setting']] = $format['setting'];
        }

        return $return;
    }

    /**
     * Converts a stored date to the user formatted date.
     *
     * @param string $date The yyyy-mm-dd standardized date
     * @param bool $includeTime Whether to include the time
     * @return string             The user formatted date
     * @throws \Exception
     */
    public static function format($date = null, $includeTime = false): string
    {
        $date = new DateTime($date);

        if (!$includeTime)
        {
            return $date->format(config('bt.dateFormat'));
        }

        //return $date->format(config('bt.dateFormat') . (!config('bt.use24HourTimeFormat') ? ' g:i A' : ' H:i'));
        return $date->format(config('bt.datetimepickerFormat'));
    }

    /**
     * Converts a user submitted date back to standard yyyy-mm-dd format.
     *
     * @param  string $userDate The user submitted date
     * @return string|null      The yyyy-mm-dd standardized date
     */
    public static function unformat($userDate = null, $includeTime = false): ?string
    {
        if ($userDate)
        {
            if (!$includeTime) {
                $date = DateTime::createFromFormat(config('bt.dateFormat'), $userDate);
                return $date->format('Y-m-d');
            }

            $date = DateTime::createFromFormat(config('bt.datetimepickerFormat'), $userDate);
            return $date->format('Y-m-d H:i:s');
        }

        return null;
    }

    /**
     * Converts a stored date to unix epoch with optional millisecond.
     *
     * @param string $date The yyyy-mm-dd h:i:s standardized date
     * @return int             The unix epoch millisecond date
     * @throws \Exception
     */
    public static function formatEpoch($date = null, $milli = null): int
    {
        $date = new DateTime($date);
        return !$milli ? strtotime($date->format('Y-m-d H:i:s')) : strtotime($date->format('Y-m-d H:i:s')) * 1000;
    }

    /**
     * Converts a stored time to the user formatted time.
     *
     * @param string $time The H:i:s standardized time
     * @return string             The user formatted time
     * @throws \Exception
     */
    public static function formattime($time = null): string
    {
        $time = new DateTime($time);

        return $time->format(!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i');
    }

    /**
     * Converts a user submitted time back to standard H:i:s format.
     *
     * @param  string $userTime The user submitted time
     * @return string|null             The H:i:s standardized time
     */
    public static function unformattime($userTime = null): ?string
    {
        if ($userTime)
        {
            $time = DateTime::createFromFormat(!config('bt.use24HourTimeFormat') ? ' h:i A' : ' H:i', $userTime);

            return $time->format('H:i:s');
        }

        return null;
    }

    /**
     * Adds a specified number of days to a yyyy-mm-dd formatted date.
     *
     * @param string $date The date
     * @param int $numDays The number of days to increment
     * @return string The yyyy-mm-dd standardized incremented date
     * @throws \Exception
     */
    public static function incrementDateByDays($date, $numDays): string
    {
        $date = DateTime::createFromFormat('Y-m-d', $date);

        $date->add(new DateInterval('P' . $numDays . 'D'));

        return $date->format('Y-m-d');
    }

    /**
     * Adds a specified number of periods to a yyyy-mm-dd formatted date.
     *
     * @param string $date The date
     * @param int $period 1 = Days, 2 = Weeks, 3 = Months, 4 = Years
     * @param int $numPeriods The number of periods to increment
     * @return string The yyyy-mm-dd standardized incremented date
     * @throws \Exception
     */
    public static function incrementDate($date, $period, $numPeriods): string
    {
        $date = DateTime::createFromFormat('Y-m-d', $date);

        switch ($period)
        {
            case 1:
                $date->add(new DateInterval('P' . $numPeriods . 'D'));
                break;
            case 2:
                $date->add(new DateInterval('P' . $numPeriods . 'W'));
                break;
            case 3:
                $date->add(new DateInterval('P' . $numPeriods . 'M'));
                break;
            case 4:
                $date->add(new DateInterval('P' . $numPeriods . 'Y'));
                break;
        }

        return $date->format('Y-m-d');
    }

    /**
     * Returns the short name of the month from a numeric representation.
     *
     * @param  int $monthNumber
     * @return string
     */
    public static function getMonthShortName($monthNumber): string
    {
        return date('M', mktime(0, 0, 0, $monthNumber, 1, date('Y')));
    }

    /**
     * Returns the format required to initialize the datepicker.
     *
     * @return string
     */
    public static function getDatepickerFormat(): string
    {
        $formats = self::formats();

        return $formats[config('bt.dateFormat')]['datepicker'];
    }
}
