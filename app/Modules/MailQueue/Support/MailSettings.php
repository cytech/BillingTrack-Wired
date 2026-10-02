<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Modules\MailQueue\Support;

class MailSettings
{
    /**
     * Provide a list of send methods.
     *
     * @return array
     */
    public static function listSendMethods()
    {
        return [
            '' => trans('bt.none'),
            'smtp' => trans('bt.email_send_method_smtp'),
            'mail' => trans('bt.email_send_method_phpmail'),
            'sendmail' => trans('bt.email_send_method_sendmail'),
        ];
    }

    /**
     * Provide a list of smtp schemes.
     *
     * @return array
     */
    public static function listSchemes()
    {
        return [
            null => 'Auto (Default)', // smtp-587, unless port set 465 then smtps
            'smtps' => 'SMTPS',
            'smtp' => 'SMTP',
        ];
    }

    /**
     * Provide a list of smtp ports.
     *
     * @return array
     */
    public static function listSmtpPorts()
    {
        return [
            null => 'Auto (Default)',
            '587' => '587',
            '465' => '465',
            '2525' => '2525',
            '25' => '25'
        ];
    }
}
