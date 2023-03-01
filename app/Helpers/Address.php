<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

function formatAddress($object)
{
    if ($object->address or $object->city or $object->state or $object->zip or $object->country)
    {
        $address = config('bt.addressFormat');

        $address = str_replace('{{ address }}', (string) $object->address, $address);
        $address = str_replace('{{ city }}', (string) $object->city, $address);
        $address = str_replace('{{ state }}', (string) $object->state, $address);
        $address = str_replace('{{ zip }}', (string) $object->zip, $address);
        $address = str_replace('{{ zip_code }}', (string) $object->zip, $address);
        $address = str_replace('{{ postal_code }}', (string) $object->zip, $address);
        return str_replace('{{ country }}', (string) $object->country, $address);
    }

    return '';
}
function formatAddress2($object)
{
    if ($object->address_2 or $object->city_2 or $object->state_2 or $object->zip_2 or $object->country_2)
    {
        $address = config('bt.addressFormat');

        $address = str_replace('{{ address }}', (string) $object->address_2, $address);
        $address = str_replace('{{ city }}', (string) $object->city_2, $address);
        $address = str_replace('{{ state }}', (string) $object->state_2, $address);
        $address = str_replace('{{ zip }}', (string) $object->zip_2, $address);
        $address = str_replace('{{ zip_code }}', (string) $object->zip_2,$address);
        $address = str_replace('{{ postal_code }}', (string) $object->zip_2, $address);
        return str_replace('{{ country }}', (string) $object->country_2, $address);
    }

    return '';
}
