<?php

namespace BT\Modules\Merchant\Support;

use BT\Support\Directory;

class MerchantFactory
{
    /**
     * @return MerchantDriver
     */
    public static function create($driver)
    {
        $driver = 'BT\\Modules\\Merchant\\Support\\Drivers\\' . $driver;

        return new $driver;
    }

    public static function getDrivers($enabledOnly = false)
    {
        $files = Directory::listContents(app_path('Modules/Merchant/Support/Drivers'));

        $drivers = [];

        foreach ($files as $file)
        {
            $file = basename($file, '.php');

            $driver = self::create($file);

            if (!$enabledOnly or $enabledOnly and $driver->getSetting('enabled'))
            {
                $drivers[$file] = $driver;
            }
        }

        return $drivers;
    }
}
